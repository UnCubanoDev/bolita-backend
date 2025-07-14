<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Services\BetValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Apuestas",
 *     description="Endpoints para gestionar las apuestas"
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

/**
 * @OA\Schema(
 *     schema="Bet",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="game_id", type="integer", example=1),
 *     @OA\Property(property="type", type="string", enum={"pick3", "pick4"}, example="pick3"),
 *     @OA\Property(property="session_time", type="string", enum={"morning", "evening"}, example="morning"),
 *     @OA\Property(property="bet_details", type="array",
 *         @OA\Items(
 *             @OA\Property(property="number", type="string", example="123"),
 *             @OA\Property(property="amount", type="number", format="float", example=10.0)
 *         )
 *     ),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=10.0),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-01T12:00:00Z")
 * )
 */


class BetController extends Controller
{
    private $betValidationService;

    public function __construct(BetValidationService $betValidationService)
    {
        $this->betValidationService = $betValidationService;
    }

    /**
     * @OA\Post(
     *     path="/bets",
     *     summary="Crear una nueva apuesta",
     *     description="Crea una nueva apuesta para el usuario autenticado.",
     *     operationId="createBet",
     *     tags={"Apuestas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"game_id", "type", "session_time", "bet_details"},
     *             @OA\Property(property="game_id", type="integer", example=1),
     *             @OA\Property(property="type", type="string", enum={"pick3", "pick4"}, example="pick3"),
     *             @OA\Property(property="session_time", type="string", enum={"morning", "evening"}, example="morning"),
     *             @OA\Property(property="bet_details", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="number", type="string", example="123"),
     *                     @OA\Property(property="amount", type="number", format="float", example=10.0)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Apuesta creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Bet")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no válidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object", example={"game_id": {"El campo game_id es obligatorio."}})
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Depuración para verificar los datos enviados
        dd($request->bet_details);

        $game = Game::find($request->game_id);

        if (!$this->betValidationService->canPlaceBetDynamic($game->name, $request->session_time)) {
            return response()->json([
                'error' => 'No se pueden realizar apuestas para este juego o sesión en este horario.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'type' => 'required|in:pick3,pick4',
            'session_time' => 'required|in:morning,evening',
            'bet_details' => 'required|array',
            'bet_details.*.number' => 'required|string',
            'bet_details.*.amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $bet = Bet::create([
            'user_id' => auth()->id(),
            'game_id' => $request->game_id,
            'type' => $request->type,
            'session_time' => $request->session_time,
            'bet_details' => $request->bet_details,
            'status' => 'pending'
        ]);

        return response()->json($bet, 201);
    }

    /**
     * @OA\Get(
     *     path="/bets",
     *     summary="Obtener todas las apuestas",
     *     description="Obtiene todas las apuestas realizadas por el usuario autenticado.",
     *     operationId="getBets",
     *     tags={"Apuestas"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de apuestas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Bet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $bets = $request->user()->bets;
        return response()->json($bets);
    }

    /**
     * @OA\Get(
     *     path="/bets/{bet}",
     *     summary="Obtener una apuesta específica",
     *     description="Obtiene una apuesta específica por su ID.",
     *     operationId="getBet",
     *     tags={"Apuestas"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="bet",
     *         in="path",
     *         required=true,
     *         description="ID de la apuesta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Apuesta encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Bet")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Apuesta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Apuesta no encontrada.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $bet = Bet::where('user_id', auth()->id())->find($id);
        if (!$bet) {
            return response()->json(['message' => 'Apuesta no encontrada'], 404);
        }
        return response()->json($bet);
    }

    /**
     * @OA\Get(
     *     path="/bets/active",
     *     summary="Obtener apuestas activas",
     *     description="Obtiene las apuestas activas del usuario autenticado.",
     *     operationId="getActiveBets",
     *     tags={"Apuestas"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de apuestas activas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Bet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     )
     * )
     */
    public function getActiveBets(Request $request)
    {
        $activeBets = Bet::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get();
        return response()->json($activeBets);
    }
}
