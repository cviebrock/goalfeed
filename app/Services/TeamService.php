<?php
namespace App\Services;

use App\Game;
use App\Team;

class TeamService extends BaseService {

	public static function isTeamAssignedToGame(Team $team, Game $game){

		foreach ($game->teams()->get() as $addedTeam) {
			if ($addedTeam->team_code == $team->team_code){
				return true;
			}
		}

		return false;
	}
	public static function assignTeamsToGame(Game $game, array $teams) {
		foreach ($teams as $team){
			if(!self::isTeamAssignedToGame($team, $game)){
				$team->games()->save($game);
			}
		}
	}
}
