<?php

namespace App\Console\Commands;

use App\Game;
use Illuminate\Console\Command;

class scheduleListeners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nhl:start-games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start listeners for games beginning in the next hour';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
		$gamesToStart = Game::where('start_time', '>', time() )
							->where('start_time', '<=', time() + 3660 )
							->where('listener_status', '=', Game::GAME_LISTENER_STATUS_IDLE)
							->get();

	    foreach ($gamesToStart as $game){
			$command = 'nhl:game-listener ' . $game->game_code;
	    	call_in_background($command);
		    $this->output->writeln('running ' . $command);
	    }
    }
}
