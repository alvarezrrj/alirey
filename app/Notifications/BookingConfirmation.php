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

/**
 * System to user notification of booking confirmation
 */

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

        $mail_message = (new MailMessage)
            ->subject(__('Your booking details'))
            ->greeting(__('Thank you for booking a session with us! These are your booking details:'))
            ->line(__('Date').': '.$this->booking->day->format('d/m/Y'))
            ->line(__('Time').': '.$this->booking->slot->start->format('H:i'))
            ->line(__('Location').': '.$location)
            ->action(__('Open on website'), route('bookings.show', $this->booking));
        if (! $this->booking->user->phone) {
            $mail_message->line(__('Your therapist\'s number')
                .': +'
                .$this->booking->therapist->code->code
                .' '
                .$this->booking->therapist->phone
            )
            ->line(__('Remember that you can have us call you by entering your phone number in your profile!'))
            ->line('['.__('Update your profile').']('.route('profile.edit').')');
        }
        return $mail_message;
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
