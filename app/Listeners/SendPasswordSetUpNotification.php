<?php

namespace App\Listeners;

use App\Notifications\SetUpYourPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordSetUpNotification
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
    public function handle(object $event): void
    {
        if (! $event->has_password) {
            $event->user->notify(new SetUpYourPassword);
        }
    }
}
