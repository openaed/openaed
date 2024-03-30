<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Remember: time is in UTC
        $schedule->call('App\Http\Controllers\API\DefibrillatorController@import')->dailyAt('05:00');
        $schedule->call('App\Http\Controllers\API\DefibrillatorController@import')->dailyAt('17:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
