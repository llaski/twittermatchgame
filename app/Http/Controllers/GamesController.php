<?php

namespace App\Http\Controllers;

use App\Game;
use App\Twitter\Twitter;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function store()
    {
        $game = Game::generate();

        return response()->json([
            'id' => $game->id,
            'tweets' => $game->tweets
        ], 201);
    }

    public function update($id)
    {
        $game = Game::findOrFail($id);

        $this->validate(request(), [
            'results' => 'required|array|size:' . $game->total_questions,
            'email' => 'required|email',
            'name' => 'required|min:2',
            'time' => 'required|numeric|min:0|max:300'
        ]);

        $game->finalizeResults(request('results'), request('email'), request('name'), request('time'));

        return response()->json([
            'rank' => (int) $game->rank,
            'email' => (string) $game->email,
            'name' => (string) $game->name,
            'time' => (int) $game->time,
        ]);
    }
}
