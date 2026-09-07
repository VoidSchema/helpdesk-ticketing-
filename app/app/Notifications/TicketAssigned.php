<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssigned extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ticket Assigned to You: #' . $this->ticket->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned a support ticket.')
            ->line('Ticket: #' . $this->ticket->id . ' - ' . $this->ticket->title)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Created by: ' . $this->ticket->creator->name)
            ->action('View Ticket', url('/tickets/' . $this->ticket->id))
            ->line('Please review and address this ticket promptly.');
    }
}
