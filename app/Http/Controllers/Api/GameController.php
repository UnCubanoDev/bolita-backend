<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;


class GameController extends Controller
{

    public function index()
    {
        $games = Game::all();
        return response()->json($games);
    }

    public function getActiveGames(Request $request)
    {
        $activeGames = Game::where('is_active', true)->get();
        return response()->json($activeGames);
    }

    public function show($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Juego no encontrado'], 404);
        }
        return response()->json($game);
    }

    public function getResults($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Juego no encontrado'], 404);
        }
        $results = $game->results; // Utiliza la relación `results`
        return response()->json($results);
    }
}
