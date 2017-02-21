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
            'results' => 'required|array|size:' . $game->total_questions
        ]);

        $game->finalizeResults(request('results'));

        return response()->json([
            'total_questions' => (int) $game->total_questions,
            'num_correct_answers' => (int) $game->num_correct_answers,
            'percentage_correct' => (int) $game->percentage_correct
        ]);
    }
}
