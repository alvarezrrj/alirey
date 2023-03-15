<?php

namespace App\Listeners;

use App\Events\NewBookingEvent;
use App\Notifications\BookingConfirmation;
use App\Notifications\NewBooking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewBookingHandler implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewBookingEvent  $event
     * @return void
     */
    public function handle(NewBookingEvent $event)
    {
        $event->booking->user->notify(new BookingConfirmation($event->booking));
        $event->booking->therapist->notify(new NewBooking($event->booking));
    }
}
