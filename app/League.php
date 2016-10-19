<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\League
 *
 * @property integer $id
 * @property string $short_name
 * @property string $long_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Team[] $teams
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Game[] $games
 * @method static \Illuminate\Database\Query\Builder|\App\League whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\League whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\League whereLongName($value)
 * @mixin \Eloquent
 */
class League extends Model
{
    //
	protected $fillable = ['short_name','long_name'];

	public $timestamps = false;

	public function teams(){
		return $this->hasMany('App\Team');
	}

	public function games(){
		return $this->hasMany('App\Game');
	}

}
