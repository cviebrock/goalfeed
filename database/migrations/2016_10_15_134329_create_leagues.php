<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	    Schema::create('leagues', function (Blueprint $table) {
		    $table->increments('id');
		    $table->text('short_name');
		    $table->text('long_name');
	    });

	    Schema::table('games', function (Blueprint $table) {
		    $table->integer('league_id');
	    });

	    Schema::table('teams', function (Blueprint $table) {
		    $table->integer('league_id');
	    });
	    DB::table('leagues')->insert(['id'=>1,'short_name'=>'NHL','long_name' => 'National Hockey League']);
	    DB::table('games')->update(['league_id'=>1]);
	    DB::table('teams')->update(['league_id'=>1]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
	    Schema::table('games', function (Blueprint $table) {
		    $table->dropColumn(['league_id']);
	    });

	    Schema::table('teams', function (Blueprint $table) {
		    $table->dropColumn(['league_id']);
	    });

	    Schema::drop('leagues');
    }
}
