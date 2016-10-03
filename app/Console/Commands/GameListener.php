<?php

namespace App\Console\Commands;

use App\EngineMiscFunctions;
use App\Game;
use App\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Curl;
use Symfony\Component\Console\Output\OutputInterface;

class GameListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nhl:game-listener {game_code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'listens';

	public $homeTeamGoals = 0;
	public $awayTeamGoals = 0;

	public $homeTeam;
	public $awayTeam;

	public $date;
	public $game;


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
	    $this->output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);

	    $this->game = Game::whereGameCode($this->argument("game_code"))->first();
	    $gameUrl = 'http://live.nhle.com/GameData/20162017/' . $this->game->game_code . '/gc/gcsb.jsonp';
		$gameActive = true;
		$this->date = Carbon::createFromTimestamp($this->game->start_time, 'America/Toronto');


	    $firstrun = true;


		try{

			while($gameActive == true){

				$response = Curl::to($gameUrl)->get();

				if($response){
					$scoreboard = EngineMiscFunctions::jsonp_decode($response);


					if($firstrun){
						$this->homeTeam = Team::whereTeamCode($scoreboard->h->ab)->first();
						$this->awayTeam = Team::whereTeamCode($scoreboard->a->ab)->first();

						$this->homeTeamGoals = $scoreboard->h->tot->g;
						$this->awayTeamGoals = $scoreboard->a->tot->g;
						$firstrun = false;
					}

					$this->checkForGoals($scoreboard);

					if($scoreboard->p > 2 && $scoreboard->sr == 0 ) {
						$gameActive = $this->checkGameOver();
					}
					///$this->output('check');
				}
				sleep(1);
			}
		}catch (Exception $e){
			$this->output->writeln($e->getLine());
		}

    }

    public function checkForGoals($scoreboard){
    	
		if($scoreboard) {
			if ($scoreboard->h->tot->g > $this->homeTeamGoals) {
				$this->goal($this->homeTeam);
				$this->homeTeamGoals = $scoreboard->h->tot->g;
			}

			if ($scoreboard->a->tot->g > $this->awayTeamGoals) {
				$this->goal($this->awayTeam);
				$this->awayTeamGoals = $scoreboard->a->tot->g;
			}
		}
    }

    public function goal(Team $team){
    	//todo
	    $teamname = $team->team_name;
	    $this->output->writeln("Goal $teamname");

    }

    public function checkGameOver() {

    	return false;

/*	    $scoreboardURL = "http://live.nhle.com/GameData/GCScoreboard/" . $this->date->toDateString() .".jsonp";

	    $response = Curl::to($scoreboardURL)->get();

	    if($response) {

		    $scoreboard = EngineMiscFunctions::jsonp_decode($response, false);

		    foreach ($scoreboard->games as $chkGame) {


		    }
	    }*/

	}
}
