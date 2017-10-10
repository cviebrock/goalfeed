<?php

namespace App\Console\Commands\MLB;

use App\Game;
use App\League;
use App\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\TeamService;
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
    protected $signature = 'mlb:get-games {days?} {offset?}';

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
	    $mlb = League::firstOrCreate(['short_name' => 'MLB', 'long_name' => 'Major League Baseball']);
	    var_dump($mlb);

		$days = $this->argument("days") ? $this->argument("days") : 7;
	    $offset = $this->argument("offset") ? $this->argument("offset") : 0;

	    $date = Carbon::today();
	    $date->addDays($offset);
	    $date->timezone('America/Toronto');
		$daysProcessed = 0;

	    $progress = new ProgressBar($this->output);
	    $progress->start($days);

	    while($daysProcessed < $days){

		    $scoreboardURL = "https://statsapi.mlb.com/api/v1/schedule?sportId=1&startDate=" . $date->format('Y-n-d') . "&endDate=" . $date->format('Y-n-d') . "&expand=schedule.teams,schedule.linescore,schedule.broadcasts.all,schedule.metadata&leaderCategories=&leaderGameTypes=R&teamId=&gameType=&timecode=";
		    //$scoreboardURL = "http://gd2.mlb.com/components/game/mlb/year_" . $date->format('Y') . "/month_" . $date->format('m') . "/day_" . $date->format('d') . "/master_scoreboard.json";
			$this->output->writeln($scoreboardURL);
		    $response = Curl::to($scoreboardURL)->get();

		    if($response){

			    //$scoreboard = EngineMiscFunctions::jsonp_decode($response, false);
			    $scoreboard = json_decode($response, false);

			    var_dump($scoreboard);
			    foreach ($scoreboard->dates[0]->games as $game) {
				    $homeTeam = Team::firstOrCreate(['team_code' => $game->teams->home->team->abbreviation, 'team_name' => ucwords(strtolower($game->teams->home->team->name)), 'league_id' => $mlb->id]);
				    $awayTeam = Team::firstOrCreate(['team_code' => $game->teams->away->team->abbreviation, 'team_name' => ucwords(strtolower($game->teams->away->team->name)), 'league_id' => $mlb->id]);

				    $startTime = Carbon::parse($game->gameDate);

				    $curGame = Game::firstOrCreate(['game_code' => $game->gamePk, 'start_time' => $startTime->timestamp, 'league_id' => $mlb->id]);

				    TeamService::assignTeamsToGame($curGame,[$homeTeam, $awayTeam]);

			    }
		    }
		    $date->addDay();
		    $daysProcessed++;
		    $progress->advance();
		    //sleep(1);
	    }



    }
}
