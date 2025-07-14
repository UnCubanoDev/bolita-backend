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


    /**
     * @OA\Get(
     *     path="/games",
     *     summary="Obtener todos los juegos",
     *     description="Obtiene todos los juegos disponibles.",
     *     operationId="getGames",
     *     tags={"Juegos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de juegos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Game")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $games = Game::all();
        return response()->json($games);
    }

    /**
     * @OA\Get(
     *     path="/games/active",
     *     summary="Obtener juegos activos",
     *     description="Obtiene los juegos activos.",
     *     operationId="getActiveGames",
     *     tags={"Juegos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de juegos activos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Game")
     *         )
     *     )
     * )
     */
    public function getActiveGames(Request $request)
    {
        $activeGames = Game::where('is_active', true)->get();
        return response()->json($activeGames);
    }

    /**
     * @OA\Get(
     *     path="/games/{game}",
     *     summary="Obtener un juego específico",
     *     description="Obtiene un juego específico por su ID.",
     *     operationId="getGame",
     *     tags={"Juegos"},
     *     @OA\Parameter(
     *         name="game",
     *         in="path",
     *         required=true,
     *         description="ID del juego",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Juego encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Juego no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Juego no encontrado.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Juego no encontrado'], 404);
        }
        return response()->json($game);
    }

    /**
     * @OA\Get(
     *     path="/games/{game}/results",
     *     summary="Obtener resultados de un juego",
     *     description="Obtiene los resultados de un juego específico.",
     *     operationId="getGameResults",
     *     tags={"Juegos"},
     *     @OA\Parameter(
     *         name="game",
     *         in="path",
     *         required=true,
     *         description="ID del juego",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultados del juego",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/GameResult")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Juego no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Juego no encontrado.")
     *         )
     *     )
     * )
     */
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
