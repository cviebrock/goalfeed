<?php

	namespace App\Console\Commands;

use App\Game;
use App\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Curl;
use App\EngineMiscFunctions;
use Symfony\Component\Console\Helper\ProgressBar;

class GetGamesForDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nhl:get-games {days?} {offset?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets games for the specified dates';

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

	    $days = $this->argument("days") ? $this->argument("days") : 1;
	    $offset = $this->argument("offset") ? $this->argument("offset") : 0;

	    $date = Carbon::today();
	    $date->addDays($offset);
	    $date->timezone('America/Toronto');
		$daysProcessed = 0;

	    $progress = new ProgressBar($this->output);
	    $progress->start($days);

	    while($daysProcessed < $days){
		    $scoreboardURL = "http://live.nhle.com/GameData/GCScoreboard/" . $date->toDateString() .".jsonp";
			$this->output->writeln($scoreboardURL);
		    $response = Curl::to($scoreboardURL)->get();

		    if($response){

			    $scoreboard = EngineMiscFunctions::jsonp_decode($response, false);

			    foreach ($scoreboard->games as $game){
				    $homeTeam = Team::firstOrCreate(['team_code' => $game->hta, 'team_name' => ucwords(strtolower($game->htn . ' ' . $game->htcommon))]);
				    $awayTeam = Team::firstOrCreate(['team_code' => $game->ata, 'team_name' => ucwords(strtolower($game->atn . ' ' . $game->atcommon))]);

				    $startTime = $date;
				    $startTime->setTimeFromTimeString(date("G:i", strtotime($game->bs)));

				    $curGame = Game::firstOrCreate(['game_code' => $game->id,'start_time' => $startTime->timestamp]);

				    $homeTeam->games()->save($curGame);
				    $awayTeam->games()->save($curGame);
			    }
		    }
		    $date->addDay();
		    $daysProcessed++;
		    $progress->advance();
		    //sleep(1);
	    }



    }
}
