<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-teams','InfoController@getAllTeams');
Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/admin', 'AdminController@getAdminDash');
Route::get('/admin/start-listener/{gamecode}', 'AdminController@getStartListener');
Route::get('/admin/game-status', 'AdminController@getGameStatus')->name('admin.game-status');

Route::get('/test-goal', 'AdminController@testGoal');
