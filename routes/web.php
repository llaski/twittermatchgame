<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@home');
Route::get('/game', function () { return redirect('/'); });
Route::get('/leaderboard', 'PagesController@home');


Route::group(['prefix' => 'api'], function () {
    Route::get('games', 'GamesController@index');
    Route::post('games', 'GamesController@store');
    Route::put('games/{id}', 'GamesController@update');
});
