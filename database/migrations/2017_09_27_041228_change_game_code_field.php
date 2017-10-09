<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGameCodeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	    \DB::table('games')->truncate();

	    Schema::table('games', function (Blueprint $table) {
		    $table->dropColumn('game_code');
	    });
	    Schema::table('games', function (Blueprint $table) {
		    $table->integer('game_code')->unique();
	    });
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

		    $table->dropColumn('game_code');
		    $table->string('game_code');

	    });
    }
}
