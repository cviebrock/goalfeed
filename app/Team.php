<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Team
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Game[] $games
 * @mixin \Eloquent
 * @property integer $id
 * @property string $team_code
 * @property string $team_name
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereTeamCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereTeamName($value)
 * @property integer $league_id
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereLeagueId($value)
 */
class Team extends Model
{
	public $timestamps = false;

	protected $fillable = ['team_code', 'team_name'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function games()
	{
		return $this->belongsToMany('App\Game', 'game_team', 'team_id', 'game_id');
	}
}
