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
    	'App\Console\Commands\NHL\GetGamesForDate',
	    'App\Console\Commands\NHL\GameListener',
	    'App\Console\Commands\NHL\sendTestEvent',
	    'App\Console\Commands\NHL\scheduleListeners',
	    'App\Console\Commands\NHL\ListenerSafetyNet',

	    'App\Console\Commands\MLB\GetGamesForDate',
	    'App\Console\Commands\MLB\GameListener',
	    'App\Console\Commands\MLB\sendTestEvent',
	    'App\Console\Commands\MLB\scheduleListeners',
	    'App\Console\Commands\MLB\ListenerSafetyNet',
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

	    $schedule->command('nhl:get-games')
		    ->daily();

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
