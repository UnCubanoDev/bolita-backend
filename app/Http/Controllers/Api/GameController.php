<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Juegos",
 *     description="Endpoints para gestionar los juegos"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Game",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juego 1"),
 *     @OA\Property(property="type", type="string", example="pick3"),
 *     @OA\Property(property="date", type="string", format="date", example="2023-10-01"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="winning_number", type="string", example="123"),
 *     @OA\Property(property="result_imported_at", type="string", format="date-time", example="2023-10-01T12:00:00Z")
 * )
 */



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
