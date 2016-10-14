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
    	'App\Console\Commands\GetGamesForDate',
	    'App\Console\Commands\GameListener',
	    'App\Console\Commands\sendTestEvent',
	    'App\Console\Commands\scheduleListeners',
	    'App\Console\Commands\ListenerSafetyNet',
        //d
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		$schedule->command('nhl:start-games')
			->hourly();

		$schedule->command('nhl:rescue-games')->everyFiveMinutes();


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
