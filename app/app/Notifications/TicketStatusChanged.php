<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket, public string $oldStatus)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = str_replace('_', ' ', ucfirst($this->ticket->status));
        $oldStatusLabel = str_replace('_', ' ', ucfirst($this->oldStatus));

        return (new MailMessage)
            ->subject('Ticket #' . $this->ticket->id . ' Status Updated')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The status of a support ticket has been updated.')
            ->line('Ticket: #' . $this->ticket->id . ' - ' . $this->ticket->title)
            ->line('Status changed: ' . $oldStatusLabel . ' → ' . $statusLabel)
            ->action('View Ticket', url('/tickets/' . $this->ticket->id));
    }
}
