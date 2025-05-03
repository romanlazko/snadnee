<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationSuccessfulyCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Reservation $reservation)
    {
        
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Your reservation has been created successfully.')
            ->line('Table: ' . $this->reservation->table->name)
            ->line('Date: ' . $this->reservation->date->format('Y-m-d'))
            ->line('Time: ' . $this->reservation->time)
            ->line('Thank you for using our application!');
    }
}
