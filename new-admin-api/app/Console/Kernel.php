<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Run every minute / every 5 minutes / hourly, depending on your need:
        // $schedule->command('subscriptions:promote-queued')->cron('*/1 * * * *'); // run every minute
        // $schedule->command('subscriptions:promote-queued')->everyMinutes();


        // Run daily at 12:00(php artisan subscriptions:promote-queued)
        $schedule->command('subscriptions:subscriber-subscription')->dailyAt('12:00');

        // Run EPG auto-updater every 10 minutes (checks for due services)
        $schedule->command('channel:epg-service')->everyTenMinutes();
    }

    // protected function schedule(Schedule $schedule)
    // {
    // Run every minute / every 5 minutes / hourly, depending on your need:
    // }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
