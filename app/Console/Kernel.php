<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Models\User;
use App\Console\Commands\AutoLeadsAssign;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\AutoLeadsAssign::class,
        // Other commands...
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:auto-leads-assign')->everyMinute()->sendOutputTo('C:\Users\TOSHIBA\Desktop\Projects\CRM_Backend\storage\logs\laravel.log');

        // $schedule->command('inspire')->hourly();
        $schedule->call([User::class, 'updateStatus'])->everyMinute();

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
