<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * System to client notification of booking confirmation. Sent when therapist
 * books on behalf of client.
 */

class BookingDetails extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Booking $booking)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $location = $this->booking->virtual
            ? 'WhatsApp'
            : 'Fleming 180, Cosquin, Cordoba';

        $mail_message = (new MailMessage)
            ->subject(__('Your booking details'))
            ->greeting(__('Hi').' '.$this->booking->user->firstName.' 👋,')
            ->line(__('These are your booking details:'))
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
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
