<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket, public Comment $comment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment on Ticket #' . $this->ticket->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->comment->user->name . ' has added a comment to ticket #' . $this->ticket->id)
            ->line('Ticket: ' . $this->ticket->title)
            ->line('Comment: ' . \Illuminate\Support\Str::limit($this->comment->body, 200))
            ->action('View Ticket', url('/tickets/' . $this->ticket->id));
    }
}
