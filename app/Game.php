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
 * @property string $listener_status
 * @method static \Illuminate\Database\Query\Builder|\App\Game whereListenerStatus($value)
 */
class Game extends Model
{
	const GAME_LISTENER_STATUS_IDLE = 'idle';
	const GAME_LISTENER_STATUS_WAITING = 'waiting';
	const GAME_LISTENER_STATUS_ACTIVE = 'active';
	const GAME_LISTENER_STATUS_DONE = 'done';
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
