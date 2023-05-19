<?php

namespace Illuminate\Auth\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyEmail
            && ! $event->user->hasVerifiedEmail()
            // If user was created by therapist, we will only send the password
            // set up email and verify their email when they set up their
            // password
            && $event->has_password) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
