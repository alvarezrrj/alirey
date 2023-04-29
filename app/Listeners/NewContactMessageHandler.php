<?php

namespace App\Listeners;

use App\Events\NewContactMessageEvent;
use App\Notifications\MessageConfirmation;
use App\Notifications\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewContactMessageHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewContactMessageEvent $event): void
    {
        // Send email to therapist
        $event->message->therapist->notify(new NewMessage($event->message));

        // Send confirmation to user
        $event->message->user->notify(new MessageConfirmation($event->message));

        // Delete message from DB
        // $event->message->delete();
    }
}
