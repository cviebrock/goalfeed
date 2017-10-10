<?php

namespace App\Console\Commands;

use App\EngineMiscFunctions;
use App\Game;
use App\Jobs\MessageSender;
use App\Message;
use App\Team;
use Carbon\Carbon;
use Curl;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;


class GameListener extends Command
{

    const COOLDOWN_TIME = 600;
    const LIVE_TIME = 9000;

    public $homeTeamGoals = false;

    public $awayTeamGoals = false;

    public $homeTeam;

    public $awayTeam;

    public $cooldownCounter;

    public $date;

    public $game;

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

        $this->cooldownCounter = self::COOLDOWN_TIME;
        $this->game = Game::whereGameCode($this->argument("game_code"))->first();
        $gameUrl = 'http://live.nhle.com/GameData/20172018/' . $this->game->game_code . '/gc/gcsb.jsonp';
        $gameActive = false;

        $this->game->listener_status = Game::GAME_LISTENER_STATUS_WAITING;
        $this->game->save();

        $this->date = Carbon::createFromTimestamp($this->game->start_time, 'America/Toronto');

        try {

            while ($gameActive == false) {
                //don't flood nhl with requests before the game is about to start
                if (time() >= $this->game->start_time - 90) {
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

                        if (($scoreboard->p > 2 && $scoreboard->sr == 0) && ($this->game->start_time + self::LIVE_TIME <= time())) {
                            $gameActive = !$this->isGameOver();
                        }
                        sleep(1);
                    }
                }
            }
        } catch (Exception $e) {
            Bugsnag::notifyError('Game Listener Exception', 'A game listener failed');
            $this->game->listener_status = Game::GAME_LISTENER_STATUS_IDLE;
            $this->game->save();
        }

        $this->game->listener_status = Game::GAME_LISTENER_STATUS_DONE;
        $this->game->save();
    }

    public function checkForGoals($scoreboard)
    {

        if ($scoreboard) {
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

    public function goal(Team $team)
    {

        $teamname = $team->team_name;
        $this->output->writeln("Goal $teamname");
        $message = new Message($team->team_code);
        dispatch(new MessageSender($message));
    }

    public function isGameOver()
    {

        $gameIsOver = false;
        if ($this->cooldownCounter > 0) {

            $urlRoot = "https://statsapi.web.nhl.com";
            $scoreboardURL = $urlRoot . "/api/v1/schedule?startDate=" . $this->date->format('Y-n-d') . "&endDate=" . $this->date->format('Y-n-d') . "&expand=schedule.teams,schedule.linescore,schedule.broadcasts.all,schedule.game.content.media.epg&leaderCategories=&leaderGameTypes=R&site=en_nhlCA&teamId=&gameType=&timecode=";
            $response = Curl::to($scoreboardURL)->get();

            if ($response) {

                $scoreboard = json_decode($response, false);

                if ($scoreboard) {
                    foreach ($scoreboard->dates[0]->games as $chkGame) {
                        if ($chkGame->gamePk == $this->game->game_code && str_contains(strtolower($chkGame->status->detailedState),
                                'final')) {
                            $this->output->writeln("Game over - cooling down - " . $this->cooldownCounter);
                            $gameIsOver = true;
                        }
                    }
                }
            }

            if ($gameIsOver) {
                $this->cooldownCounter--;
            } else {
                $this->cooldownCounter = self::COOLDOWN_TIME;
            }

            return false;
        } else {
            return true;
        }
    }
}
