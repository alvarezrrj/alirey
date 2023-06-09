<?php

namespace App\Providers;

use App\Events\NewBookingEvent;
use App\Events\NewContactMessageEvent;
use App\Listeners\NewBookingHandler;
use App\Listeners\NewContactMessageHandler;
use App\Listeners\SendPasswordSetUpNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        NewBookingEvent::class => [
            NewBookingHandler::class
        ],

        Registered::class => [
            SendEmailVerificationNotification::class,
            SendPasswordSetUpNotification::class,
        ],

        NewContactMessageEvent::class => [
            NewContactMessageHandler::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
