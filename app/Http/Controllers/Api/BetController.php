<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Bet;
use App\Services\BetValidationService;
use Illuminate\Support\Facades\Validator;

class BetController extends Controller
{
    private $betValidationService;

    public function __construct(BetValidationService $betValidationService)
    {
        $this->betValidationService = $betValidationService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_name' => 'required|string',
            'session' => 'required|string',
            'variant' => 'required|string',
            'bet_details' => 'required|array|min:1',
            'bet_details.*.number' => 'required|string',
            'bet_details.*.amount' => 'required|numeric|min:1',
        ]);

        // Buscar o crear el juego automáticamente
        $game = \App\Models\Game::firstOrCreate([
            'name' => $validated['game_name'],
            'date' => $validated['session'],
            'type' => $validated['variant'],
        ], [
            'is_active' => true,
        ]);

        // Calcular el total apostado
        $totalAmount = collect($validated['bet_details'])->sum('amount');

        // Crear la apuesta asociada al juego encontrado/creado
        $bet = \App\Models\Bet::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id, // Se asigna automáticamente, el usuario NO lo envía
            'type' => $validated['variant'],
            'bet_details' => $validated['bet_details'],
            'session_time' => $request->input('session_time', null), // si aplica
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return response()->json(['bet' => $bet], 201);
    }
    public function index(Request $request)
    {
        $bets = $request->user()->bets;
        return response()->json($bets);
    }
    public function show($id)
    {
        $bet = Bet::where('user_id', auth()->id())->find($id);
        if (!$bet) {
            return response()->json(['message' => 'Apuesta no encontrada'], 404);
        }
        return response()->json($bet);
    }
    public function getActiveBets(Request $request)
    {
        $activeBets = Bet::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get();
        return response()->json($activeBets);
    }
}
