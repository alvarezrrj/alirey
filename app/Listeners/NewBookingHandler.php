<?php

namespace App\Listeners;

use App\Events\NewBookingEvent;
use App\Http\Controllers\GoogleCalController;
use App\Notifications\BookingConfirmation;
use App\Notifications\BookingDetails;
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
        if ($event->madeByTherapist) {
            $event->booking->user->notify(new BookingDetails($event->booking));
        } else {
            $event->booking->user->notify(new BookingConfirmation($event->booking));
            $event->booking->therapist->notify(new NewBooking($event->booking));
        }

        if ($event->booking->therapist->config->google_sync) {
            GoogleCalController::store($event->booking);
        }
    }
}
