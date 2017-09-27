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

		$days = $this->argument("days") ? $this->argument("days") : 7;
	    $offset = $this->argument("offset") ? $this->argument("offset") : 0;

	    $urlRoot = "https://statsapi.web.nhl.com";

	    $date = Carbon::today();
	    $date->addDays($offset);
	    $date->timezone('America/Toronto');
		$daysProcessed = 0;

	    $progress = new ProgressBar($this->output);
	    $progress->start($days);

	    while($daysProcessed < $days){
		    $scoreboardURL = $urlRoot . "/api/v1/schedule?startDate=" . $date->format('Y-n-d') . "&endDate=" . $date->format('Y-n-d') ."&expand=schedule.teams,schedule.linescore,schedule.broadcasts.all,schedule.ticket,schedule.game.content.media.epg,schedule.radioBroadcasts,schedule.metadata,schedule.game.seriesSummary,seriesSummary.series&leaderCategories=&leaderGameTypes=R&site=en_nhlCA&teamId=&gameType=&timecode=";
			$this->output->writeln($scoreboardURL);
		    $response = Curl::to($scoreboardURL)->get();

		    if($response){

			    $scoreboard = json_decode($response, false);
			    var_dump($scoreboard);
			    if(!empty($scoreboard->dates)){

				    foreach ($scoreboard->dates[0]->games as $game){

					    //die();
					    $homeTeam = Team::firstOrCreate(['team_code' => $game->teams->home->team->abbreviation, 'team_name' => ucwords(strtolower($game->teams->home->team->name))]);
					    $awayTeam = Team::firstOrCreate(['team_code' => $game->teams->away->team->abbreviation, 'team_name' => ucwords(strtolower($game->teams->away->team->name))]);

					    $startTime = Carbon::parse($game->gameDate);
					    $curGame = Game::firstOrCreate(['game_code' => $game->gamePk,'start_time' => $startTime->timestamp]);

					    $homeTeam->games()->save($curGame);
					    $awayTeam->games()->save($curGame);
				    }

			    }
		    }
		    $date->addDay();
		    $daysProcessed++;
		    $progress->advance();
		    //sleep(1);
	    }



    }
}
