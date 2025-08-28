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
        $schedule->command('command:access_v3')
            ->cron('*/30 * * * *');
        
        // $schedule->command('command:subscription')
        //     ->cron('1,4,6,9,11,14,16,19,21,24,26,29,31,34,36,39,41,44,46,49,51,54,56,59 * * * *');
            
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
