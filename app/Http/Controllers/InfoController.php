<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class InfoController extends Controller
{

    //
    public function getAllTeams()
    {
        $teams = Team::all()->sortBy('team_name');
        $responseObject = [];

        foreach ($teams as $team) {
            $teamInfo = [
                'team_name' => $team->team_name,
                'team_code' => strtolower($team->team_code),
            ];

            $responseObject[] = $teamInfo;
        }

        $response = new JsonResponse();
        $response->setData($responseObject);

        return $response;
    }
}
