<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Game
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Team[] $teams
 * @mixin \Eloquent
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $start_time
 * @property string $game_code
 * @method static \Illuminate\Database\Query\Builder|\App\Game whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Game whereStartTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Game whereGameCode($value)
 */
class Game extends Model
{
	//
	protected $fillable = ['game_code','start_time'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function teams()
	{
		return $this->belongsToMany('App\Team','game_team','game_id','team_id');
	}
}
