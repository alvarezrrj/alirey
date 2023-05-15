<?php

namespace App\Console;

use App\Http\Controllers\BookingController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            BookingController::purge_unpaid_bookings();
        })
        ->everyMinute()
        ->appendOutputTo(base_path('logs/booking_purge_sch.log'));

        $schedule->call(function() {
            BookingController::send_reminder();
        })
        ->everyMinute()
        ->appendOutputTo(base_path('logs/booking_reminder_sch.log'));

        // Clear expired password reset tokens
        $schedule->command('auth:clear-resets')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
