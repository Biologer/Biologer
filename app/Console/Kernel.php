<?php

namespace App\Console;

use App\Models\PublicationAttachment;
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
        $schedule->command('exports:clear')->daily();
        $schedule->command('imports:clear')->daily();
        $schedule->command('uploads:clean')->dailyAt('03:00');
        $schedule->command('photos:watermark')->dailyAt('04:00');

        $schedule->call(function () {
            PublicationAttachment::deleteOldUnattached();
        })->dailyAt('03:05');

        // $schedule->command('photos:clean')->dailyAt('03:00');

        // Backup
        if (config('biologer.backup_enabled')) {
            $schedule->command('backup:clean')->dailyAt('03:15');

            $backupCommand = 'backup:run'.(config('biologer.backup_full') ? '' : ' --only-db');
            $schedule->command($backupCommand)->dailyAt('04:00');
        }

        $schedule->command('send:notifications-summary')->dailyAt('09:00');
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
