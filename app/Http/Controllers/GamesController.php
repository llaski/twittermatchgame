<?php

namespace App\Http\Controllers;

use App\Game;
use App\Twitter\Twitter;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function index()
    {
        $games = Game::topTenRankedGames();

        return response()->json([
            'games' => $games->map(function($game) {
                return [
                    'id' => $game->id,
                    'rank' => $game->rank,
                    'name' => $game->name,
                    'total_questions' => $game->total_questions,
                    'num_correct_answers' => $game->num_correct_answers,
                    'time' => $game->time,
                ];
            })
        ]);
    }

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
            'id' => (int) $game->id,
            'rank' => (int) $game->rank,
            'num_correct_answers' => (int) $game->num_correct_answers,
            'total_questions' => (int) $game->total_questions,
            'name' => (string) $game->name,
            'time' => (int) $game->time,
        ]);
    }
}
