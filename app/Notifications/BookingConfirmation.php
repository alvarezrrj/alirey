<?php

/**
 * BookingConfirmation
 * 
 * Emails user their booking details 
 */

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $location = $this->booking->virtual
            ? 'WhatsApp'
            : 'Fleming 180, Cosquin, Cordoba';

        return (new MailMessage)
            ->subject(__('Your booking details'))
            ->greeting(__('Thank you for booking a session with us! These are your booking details:'))
            ->line(__('Date').': '.$this->booking->day->format('d/m/Y'))
            ->line(__('Time').': '.$this->booking->slot->start->format('H:i'))
            ->line(__('Location').': '.$location)
            ->action(__('Open on website'), route('user.bookings.show', $this->booking));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
