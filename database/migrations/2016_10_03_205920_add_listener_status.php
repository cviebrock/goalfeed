<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Game;

class AddListenerStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	    Schema::table('games', function ($table) {
			$table->string('listener_status')->default(Game::GAME_LISTENER_STATUS_IDLE);
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
	    Schema::table('games', function ($table) {
		    $table->dropColumn('listener_status');
	    });
    }
}
