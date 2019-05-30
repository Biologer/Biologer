<?php

namespace App\Console;

use App\PublicationAttachment;
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
        // Clean up
        $schedule->command('uploads:clean')->dailyAt('03:00');

        $schedule->call(function () {
            PublicationAttachment::deleteOldUnattached();
        })->dailyAt('03:05');

        // $schedule->command('photos:clean')->dailyAt('03:00');

        // Backup
        $schedule->command('backup:clean')->dailyAt('03:15');
        $schedule->command('backup:run')->dailyAt('04:00');
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
