<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking){}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your booking is confirmed')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('Your booking for ticket #'.$this->booking->ticket_id.' has been confirmed.')
            ->line('Quantity: '.$this->booking->quantity)
            ->line('Thank you for using our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'ticket_id'  => $this->booking->ticket_id,
            'status'     => 'confirmed',
        ];
    }
}
