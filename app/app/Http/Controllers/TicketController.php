<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Attachment;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use App\Notifications\TicketCreated;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::with(['category', 'creator', 'assignee']);

        // Users see only their own tickets
        if ($user->isUser()) {
            $query->where('created_by', $user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Quick filters
        if ($request->filled('filter')) {
            match ($request->filter) {
                'my' => $query->where('assigned_to', $user->id),
                'unassigned' => $query->whereNull('assigned_to'),
                'high' => $query->where('priority', 'high'),
                default => null,
            };
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,log,csv',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'open';

        unset($validated['attachments']);

        $ticket = Ticket::create($validated);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('attachments', $filename, 'public');

                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Send email notification to agents/admins (check preferences)
        $agents = User::whereIn('role', [Role::Admin, Role::Agent])
            ->where('is_active', true)
            ->get()
            ->filter(function ($agent) {
                return $agent->notification_preferences['new_ticket'] ?? true;
            });

        Notification::send($agents, new TicketCreated($ticket));

        // Create in-app notifications
        foreach ($agents as $agent) {
            NotificationModel::create([
                'user_id' => $agent->id,
                'type' => 'ticket_created',
                'title' => 'New Ticket #' . $ticket->id,
                'message' => $ticket->title,
                'ticket_id' => $ticket->id,
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $ticket->load(['category', 'creator', 'assignee', 'comments.user']);
        $agents = User::whereIn('role', [Role::Admin, Role::Agent])->where('is_active', true)->get();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'priority' => 'sometimes|in:low,medium,high,urgent',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $oldAssigneeId = $ticket->assigned_to;
        $ticket->update($validated);

        // If assigning for the first time, change status to in_progress
        if ($ticket->wasRecentlyCreated === false && $ticket->assigned_to && $ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // Send notification to newly assigned agent
        if ($ticket->assigned_to && $ticket->assigned_to !== $oldAssigneeId) {
            $assignee = User::find($ticket->assigned_to);
            if ($assignee && ($assignee->notification_preferences['ticket_assigned'] ?? true)) {
                // Email notification
                $assignee->notify(new TicketAssigned($ticket));

                // In-app notification
                NotificationModel::create([
                    'user_id' => $assignee->id,
                    'type' => 'ticket_assigned',
                    'title' => 'Ticket #' . $ticket->id . ' assigned to you',
                    'message' => $ticket->title,
                    'ticket_id' => $ticket->id,
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket assigned successfully.');
    }

    public function changeStatus(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $newStatus = $validated['status'];
        $user = Auth::user();
        $currentStatus = $ticket->status;

        // Role-based status transitions
        if ($user->isUser()) {
            // User can only reopen resolved tickets
            $validTransitions = [
                'resolved' => ['in_progress'],
            ];
        } elseif ($user->isAgent()) {
            // Agent: Open → In Progress → Resolved
            $validTransitions = [
                'open' => ['in_progress'],
                'in_progress' => ['resolved'],
            ];
        } else {
            // Admin: full control
            $validTransitions = [
                'open' => ['in_progress', 'resolved'],
                'in_progress' => ['open', 'resolved'],
                'resolved' => ['in_progress'],
            ];
        }

        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            return back()->withErrors(['status' => 'You cannot change status from ' . str_replace('_', ' ', $currentStatus) . ' to ' . str_replace('_', ' ', $newStatus)]);
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        // Send notification to ticket creator
        if ($ticket->creator && $ticket->created_by !== Auth::id() && ($ticket->creator->notification_preferences['status_changed'] ?? true)) {
            $ticket->creator->notify(new TicketStatusChanged($ticket, $currentStatus));

            // In-app notification
            NotificationModel::create([
                'user_id' => $ticket->created_by,
                'type' => 'status_changed',
                'title' => 'Ticket #' . $ticket->id . ' status updated',
                'message' => 'Status: ' . str_replace('_', ' ', $currentStatus) . ' → ' . str_replace('_', ' ', $newStatus),
                'ticket_id' => $ticket->id,
            ]);
        }

        // Also notify assignee if they're not the one making the change
        if ($ticket->assignee && $ticket->assigned_to !== Auth::id() && ($ticket->assignee->notification_preferences['status_changed'] ?? true)) {
            $ticket->assignee->notify(new TicketStatusChanged($ticket, $currentStatus));

            // In-app notification
            NotificationModel::create([
                'user_id' => $ticket->assigned_to,
                'type' => 'status_changed',
                'title' => 'Ticket #' . $ticket->id . ' status updated',
                'message' => 'Status: ' . str_replace('_', ' ', $currentStatus) . ' → ' . str_replace('_', ' ', $newStatus),
                'ticket_id' => $ticket->id,
            ]);
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket status changed to ' . str_replace('_', ' ', $newStatus) . '.');
    }

    private function authorizeTicket(Ticket $ticket): void
    {
        $user = Auth::user();

        // Users can only access their own tickets
        if ($user->isUser() && $ticket->created_by !== $user->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }
    }
}
