<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\AccessToken::class,
        Commands\Subscription::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('command:access_token')
            ->cron('1,6,11,16,21,26,31,36,41,46,51,56 * * * *');
    
        $schedule->command('command:subscription')
            ->cron('2,7,12,17,22,27,32,37,42,47,52,57 * * * *');
            
        $schedule->command('command:user_update')
            ->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
