<?php

namespace App\Console;

use App\Jobs\GetOnlineUsers;
use App\Jobs\GetStaffData;
use App\Jobs\UpdateUsers;
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
         $schedule->job(GetOnlineUsers::class)->everyFiveMinutes();
         //much less often changed, we don't care about rank changes that much.
         $schedule->job(GetStaffData::class)->everyThirtyMinutes();
         //update all records daily at 2am
         $schedule->job(UpdateUsers::class)->dailyAt('2:00');
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
