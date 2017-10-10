<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests;
use App\Jobs\MessageSender;
use App\Message;
use Illuminate\Http\Request;


class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getAdminDash()
    {
        return view('admin.landing');
    }

    public function getGameStatus()
    {
        $timeBack = time() - 86400;
        $games = Game::where('start_time', '>', $timeBack)
            ->orderBy('start_time', 'asc')
            ->limit(20)
            ->get();

        return view('admin.game-status.game-status', ['games' => $games]);
    }

    public function getStartListener($gamecode)
    {
        $game = Game::whereGameCode($gamecode)->first();

        if ($game && ($game->listener_status == Game::GAME_LISTENER_STATUS_IDLE || $game->listener_status == Game::GAME_LISTENER_STATUS_DONE)) {
            $command = 'nhl:game-listener ' . $game->game_code;
            call_in_background($command);

            return redirect()->route('admin.game-status');
        }
    }

    public function testGoal()
    {
        $message = new Message('wpg');
        dispatch(new MessageSender($message));
    }
}
