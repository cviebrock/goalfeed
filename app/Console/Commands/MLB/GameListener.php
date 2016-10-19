<?php

namespace App\Console\Commands\MLB;

use App\EngineMiscFunctions;
use App\Game;
use App\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Curl;
use Symfony\Component\Console\Output\OutputInterface;
use App\Message;
use App\Jobs\MessageSender;

class GameListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlb:game-listener {game_code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'listens';

	public $homeTeamGoals = false;
	public $awayTeamGoals = false;

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
	    $gameUrl = 'http://gd2.mlb.com/components/game/mlb/' . $this->game->game_code . '/gc/gcsb.jsonp';
		$gameActive = false;

	    $this->game->listener_status = Game::GAME_LISTENER_STATUS_WAITING;
	    $this->game->save();

		$this->date = Carbon::createFromTimestamp($this->game->start_time, 'America/Toronto');

		try {

			while ($gameActive == false) {
				//don't flood mlb with requests before the game is about to start
				if(time() >= $this->game->start_time - 90 ){
					$this->game->listener_status = Game::GAME_LISTENER_STATUS_ACTIVE;
					$this->game->save();
					$gameActive = true;
				} else {
					sleep(60);
				}
			}

			while ($gameActive == true) {

				$response = Curl::to($gameUrl)->get();

				if ($response) {
					$scoreboard = EngineMiscFunctions::jsonp_decode($response);

					if ($scoreboard) {
						if ($this->homeTeamGoals === false || $this->awayTeamGoals === false) {
							$this->homeTeam = Team::whereTeamCode($scoreboard->h->ab)->first();
							$this->awayTeam = Team::whereTeamCode($scoreboard->a->ab)->first();

							$this->output->writeln("Game started");
							$this->homeTeamGoals = $scoreboard->h->tot->g;
							$this->awayTeamGoals = $scoreboard->a->tot->g;
						}

						$this->checkForGoals($scoreboard);

						if ($scoreboard->p > 2 && $scoreboard->sr == 0) {
							$gameActive = $this->checkGameOver();
						}
						sleep(1);
					}
				}
			}

		}catch (Exception $e){

			$this->output->writeln($e->getLine());
		}

		$this->game->listener_status = Game::GAME_LISTENER_STATUS_DONE;
	    $this->game->save();
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

	    $teamname = $team->team_name;
	    $this->output->writeln("Goal $teamname");
	    $message = new Message($team->team_code);
	    dispatch(new MessageSender($message));

    }

    public function checkGameOver() {

	    $scoreboardURL = "http://live.mlbe.com/GameData/GCScoreboard/" . $this->date->toDateString() .".jsonp";

	    $response = Curl::to($scoreboardURL)->get();

	    if($response) {

		    $scoreboard = EngineMiscFunctions::jsonp_decode($response, false);

		    if($scoreboard){
				foreach ($scoreboard->games as $chkGame) {
					if($chkGame->id == $this->game->game_code && str_contains(strtolower($chkGame->bsc),'final')){
						$this->output->writeln("Game over");
						return true;
					}
				}
			}
	    }

	    return false;
    }
}
