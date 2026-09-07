<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'is_internal' => 'boolean',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,log,csv',
        ]);

        $isInternal = false;
        if (!Auth::user()->isUser()) {
            $isInternal = $request->boolean('is_internal');
        }

        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'],
            'is_internal' => $isInternal,
        ]);

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

        // Send notification to ticket participants (except the commenter)
        $participants = collect();

        // Add ticket creator
        if ($ticket->created_by !== Auth::id()) {
            $participants->push($ticket->creator);
        }

        // Add assignee
        if ($ticket->assigned_to && $ticket->assigned_to !== Auth::id()) {
            $participants->push($ticket->assignee);
        }

        // Send notifications (filter out nulls and check preferences)
        $participants->filter()->each(function ($user) use ($ticket, $comment) {
            if ($user->notification_preferences['new_comment'] ?? true) {
                // Email notification
                $user->notify(new NewComment($ticket, $comment));

                // In-app notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'new_comment',
                    'title' => 'New comment on Ticket #' . $ticket->id,
                    'message' => $comment->user->name . ': ' . \Illuminate\Support\Str::limit($comment->body, 100),
                    'ticket_id' => $ticket->id,
                ]);
            }
        });

        return redirect()->route('tickets.show', $ticket)->with('success', 'Comment added successfully.');
    }
}
