<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $isAdminOrAgent = $user->isAdmin() || $user->isAgent();

    // Base query
    $query = Ticket::with(['category', 'creator', 'assignee']);

    if ($user->isUser()) {
        $query->where('created_by', $user->id);
    }

    // Stats
    $stats = [
        'total' => (clone $query)->count(),
        'open' => (clone $query)->where('status', 'open')->count(),
        'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
        'resolved' => (clone $query)->where('status', 'resolved')->count(),
    ];

    // Additional stats for agents/admins
    if ($isAdminOrAgent) {
        $stats['unassigned'] = Ticket::whereNull('assigned_to')->where('status', 'open')->count();
        $stats['my_tickets'] = Ticket::where('assigned_to', $user->id)->whereIn('status', ['open', 'in_progress'])->count();
        $stats['urgent'] = Ticket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count();
    }

    // Recent tickets
    $recentTickets = (clone $query)->latest()->take(10)->get();

    // Recent activity (comments)
    $recentActivity = \App\Models\Comment::with(['user', 'ticket'])
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard', compact('stats', 'recentTickets', 'recentActivity', 'isAdminOrAgent'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/notifications', [\App\Http\Controllers\ProfileController::class, 'updateNotifications'])->name('profile.notifications');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Portal
    Route::get('/my-tickets', function () {
        $user = auth()->user();
        $query = Ticket::where('created_by', $user->id)->with(['category', 'assignee']);

        if (request('filter')) {
            $query->where('status', request('filter'));
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Ticket::where('created_by', $user->id)->count(),
            'in_progress' => Ticket::where('created_by', $user->id)->where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('created_by', $user->id)->where('status', 'resolved')->count(),
        ];

        return view('portal.my-tickets', compact('tickets', 'stats'));
    })->name('portal.my-tickets');

    // Tickets
    Route::get('/tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [\App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/tickets/{ticket}/assign', [\App\Http\Controllers\TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/status', [\App\Http\Controllers\TicketController::class, 'changeStatus'])->name('tickets.status');

    // Comments
    Route::post('/tickets/{ticket}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

    // Attachments
    Route::get('/attachments/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [\App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
});

require __DIR__.'/auth.php';
