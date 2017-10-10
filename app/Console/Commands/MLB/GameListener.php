<?php

namespace App\Console\Commands\MLB;

use App\EngineMiscFunctions;
use App\Game;
use App\Team;
use App\League;
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
	    $mlb = League::firstOrCreate(['short_name' => 'MLB', 'long_name' => 'Major League Baseball']);
	    $this->game = Game::whereGameCode($this->argument("game_code"))->first();

	    $gameUrl = "http://statsapi.mlb.com/api/v1/game/" . $this->game->game_code . "/feed/live.json";
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
					$scoreboard = json_decode($response);

					if ($scoreboard) {
						if ($this->homeTeamGoals === false || $this->awayTeamGoals === false) {
							$this->homeTeam = Team::whereTeamCode($scoreboard->gameData->teams->home->name->abbrev)
								->whereLeagueId($mlb->id)->first();
							$this->awayTeam = Team::whereTeamCode($scoreboard->gameData->teams->away->name->abbrev)
								->whereLeagueId($mlb->id)->first();

							$this->output->writeln("Game started");
							$this->homeTeamGoals = $scoreboard->liveData->linescore->home->runs;
							$this->awayTeamGoals = $scoreboard->liveData->linescore->away->runs;
						}

						$this->checkForGoals($scoreboard);

						if ($scoreboard->gameData->status->statusCode == "F") {
							$this->output->writeln("Game over");
							return true;
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
			if ($scoreboard->liveData->linescore->home->runs > $this->homeTeamGoals) {
				$this->goal($this->homeTeam);
				$this->homeTeamGoals = $scoreboard->liveData->linescore->home->runs;
			}

			if ($scoreboard->liveData->linescore->away->runs > $this->awayTeamGoals) {
				$this->goal($this->awayTeam);
				$this->awayTeamGoals = $scoreboard->liveData->linescore->away->runs;
			}
		}
    }

    public function goal(Team $team){

	    $teamname = $team->team_name;
	    $this->output->writeln("Goal $teamname");
	    $message = new Message($team);
	    dispatch(new MessageSender($message));

    }
}
