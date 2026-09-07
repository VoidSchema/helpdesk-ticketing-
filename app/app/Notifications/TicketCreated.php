<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreated extends Notification
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
            ->subject('New Ticket #' . $this->ticket->id . ': ' . $this->ticket->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new support ticket has been created.')
            ->line('Ticket: #' . $this->ticket->id . ' - ' . $this->ticket->title)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Category: ' . ($this->ticket->category->name ?? 'N/A'))
            ->action('View Ticket', url('/tickets/' . $this->ticket->id))
            ->line('Thank you for using our helpdesk!');
    }
}
