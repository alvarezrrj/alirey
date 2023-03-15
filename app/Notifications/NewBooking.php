<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBooking extends Notification
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
        $type = $this->booking->virtual
            ? __('Virtual')
            : __('In-person');
        return (new MailMessage)
            ->subject(__('New Booking'))
            ->greeting(__('You have a new booking:'))
            ->line(__('Name').': '.$this->booking->user->firstName
                                  .' '
                                  .$this->booking->user->lastName)
            ->line(__('Date').': '.$this->booking->day->format('d/m/Y'))
            ->line(__('Time').': '.$this->booking->slot->start->format('H:i'))
            ->line(__('Type').': '.$type)
            ->action(__('Open on website'), route('bookings.show', $this->booking))
            ->salutation(' ');
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
