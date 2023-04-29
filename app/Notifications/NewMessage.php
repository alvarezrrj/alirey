<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

// ShouldQueue is a contract that allows us to queue the notification
// instead of sending it immediately. This keeps the app responsive.
class NewMessage extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     */
    public function __construct(private ContactMessage $message)
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
        return (new MailMessage)
            ->from($this->message->email, $this->message->name)
            ->replyTo($this->message->email, $this->message->name)
            ->subject($this->message->subject)
            ->greeting(__('You have received a new message from the web:'))
            ->line(__('Sender').': '.$this->message->name)
            ->line(__('Message').': '.$this->message->message)
            ->line(__('To send a response, reply to this email.'))
            ->salutation(' ');
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
