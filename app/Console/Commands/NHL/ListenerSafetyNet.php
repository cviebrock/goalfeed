<?php

namespace App\Console\Commands\NHL;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Game;


class ListenerSafetyNet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nhl:rescue-games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rescue games that ended early';

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
	    $nhl = League::whereShortName('NHL')->first();

		$timeBack = time() - 9000;
	    $timeAhead = time() + 1700;

	    $gamesToRescue = Game::where('start_time','<', $timeAhead)
		    ->where('start_time','>',$timeBack)
		    ->where('league_id', '=', $nhl->id)
		    ->whereNotIn('listener_status', [Game::GAME_LISTENER_STATUS_ACTIVE, Game::GAME_LISTENER_STATUS_WAITING])
		    ->get();


		$this->output->writeln('Count: ' . $gamesToRescue->count() . ' timeback: ' . $timeBack . ' timeahead: ' . $timeAhead);

	    if($gamesToRescue->count() > 0){

	    	foreach ($gamesToRescue as $game){
			    $command = 'nhl:game-listener ' . $game->game_code;
			    call_in_background($command);
			    $this->output->writeln('running ' . $command);
		    }
		    Bugsnag::notifyError('Game Rescue', 'A game had to be rescued. Please investigate');

	    }

    }
}
