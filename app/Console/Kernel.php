<?php

namespace App\Console;

use App\Http\Controllers\UserBookingController;
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
        // $schedule->command('inspire')->hourly();
        $schedule->call(function() {
            UserBookingController::purge_unpaid_bookings();
        })
        ->everyMinute()
        ->appendOutputTo(base_path('logs/booking_purge_sch.log'));

        $schedule->call(function() {
            UserBookingController::send_reminder();
        })
        ->everyMinute()
        ->appendOutputTo(base_path('logs/booking_reminder_sch.log'));
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
