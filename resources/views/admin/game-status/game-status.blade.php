@extends('admin.layouts.admin')

@section('content')
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
                <thead>
                <tr>
                    <th>Gamecode</th>
                    <th>Start Time (Central Time)</th>
                    <th>Teams</th>
                    <th>Listener Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($games as $game)
                    <tr>
                        <td>{{$game->game_code}}</td>
                        <td>{{$game->readableStartTime()}}</td>
                        <td>

                            <ul>
                                @foreach($game->teams as $team)
                                    <li>{{$team->team_name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{$game->listener_status}} <br/>
                            @if($game->listener_status == 'idle' || $game->listener_status == 'done'  )
                                <a href="/admin/start-listener/{{$game->game_code}}">Start Listener</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
