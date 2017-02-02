<?php

namespace App\Http\Controllers;

use App\Twitter\Twitter;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    private $twitter;

    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    public function start()
    {
        $data = $this->twitter->generateGameData();

        return response()->json([
            'data' => $data
        ]);
    }
}
