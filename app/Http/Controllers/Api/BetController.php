<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Bet;
use App\Services\BetValidationService;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

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

        // Validar horario de apuestas
        $currentTime = now();
        $gameName = $validated['game_name'];
        $variant = $validated['variant'];

        // Obtener horarios desde Settings
        $lotteryKey = strtolower(str_replace(' ', '', $gameName)); // "Georgia Lottery" -> "georgia"

        // Obtener horarios de mañana y tarde para determinar el periodo actual
        $morningStartKey = "{$lotteryKey}_morning_start";
        $morningEndKey = "{$lotteryKey}_morning_end";
        $eveningStartKey = "{$lotteryKey}_evening_start";
        $eveningEndKey = "{$lotteryKey}_evening_end";

        $morningStart = Setting::get($morningStartKey);
        $morningEnd = Setting::get($morningEndKey);
        $eveningStart = Setting::get($eveningStartKey);
        $eveningEnd = Setting::get($eveningEndKey);

        if ($morningStart && $morningEnd && $eveningStart && $eveningEnd) {
            $morningStart = \Carbon\Carbon::parse($morningStart);
            $morningEnd = \Carbon\Carbon::parse($morningEnd);
            $eveningStart = \Carbon\Carbon::parse($eveningStart);
            $eveningEnd = \Carbon\Carbon::parse($eveningEnd);

            // Determinar el periodo actual basado en las configuraciones
            $isMorning = $currentTime->between($morningStart, $morningEnd);
            $isEvening = $currentTime->between($eveningStart, $eveningEnd);

            if ($isMorning) {
                $period = 'morning';
                $startTime = $morningStart;
                $endTime = $morningEnd;
            } elseif ($isEvening) {
                $period = 'evening';
                $startTime = $eveningStart;
                $endTime = $eveningEnd;
            } else {
                // Fuera de horarios de apuestas
                if ($currentTime->gt($eveningEnd)) {
                    // Después del horario de tarde, ir a la siguiente sesión
                    $nextSession = $this->calculateNextSession($validated['session']);

                    $game = Game::firstOrCreate([
                        'name' => $gameName,
                        'date' => $nextSession,
                        'type' => $variant,
                    ], [
                        'is_active' => true,
                    ]);

                    $message = "Apuesta registrada para la siguiente sesión ({$nextSession}) - Fuera del horario de apuestas";
                } elseif ($currentTime->lt($morningStart)) {
                    // Antes del horario de mañana, usar sesión actual
                    $game = Game::firstOrCreate([
                        'name' => $gameName,
                        'date' => $validated['session'],
                        'type' => $variant,
                    ], [
                        'is_active' => true,
                    ]);

                    $message = "Apuesta registrada para la sesión actual - Horario de apuestas mañana: {$morningStart->format('H:i')} - {$morningEnd->format('H:i')}";
                } else {
                    // Entre horarios (gap entre mañana y tarde)
                    $game = Game::firstOrCreate([
                        'name' => $gameName,
                        'date' => $validated['session'],
                        'type' => $variant,
                    ], [
                        'is_active' => true,
                    ]);

                    $message = "Apuesta registrada para la sesión actual - Horario de apuestas tarde: {$eveningStart->format('H:i')} - {$eveningEnd->format('H:i')}";
                }

                $totalAmount = collect($validated['bet_details'])->sum('amount');
                $user = auth()->user();

                // Verificar saldo disponible (no congelado)
                if ($user->available_balance < $totalAmount) {
                    return response()->json([
                        'error' => 'Saldo disponible insuficiente para realizar la apuesta',
                        'available_balance' => $user->available_balance,
                        'frozen_balance' => $user->frozen_balance,
                        'total_balance' => $user->wallet_balance,
                        'required_amount' => $totalAmount
                    ], 400);
                }

                // Descontar del saldo total
                $user->decrement('wallet_balance', $totalAmount);

                $bet = Bet::create([
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'type' => $validated['variant'],
                    'bet_details' => $validated['bet_details'],
                    'session_time' => $request->input('session_time', null),
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                ]);

                return response()->json([
                    'bet' => $bet,
                    'message' => $message,
                    'balance_info' => [
                        'available_balance' => $user->available_balance,
                        'frozen_balance' => $user->frozen_balance,
                        'total_balance' => $user->wallet_balance
                    ]
                ], 201);
            }

            // Dentro del horario de apuestas (mañana o tarde)
            $game = Game::firstOrCreate([
                'name' => $gameName,
                'date' => $validated['session'],
                'type' => $variant,
            ], [
                'is_active' => true,
            ]);

            $message = "Apuesta registrada para la sesión actual ({$period})";
        } else {
            // Si no hay configuración de horario, usar la sesión actual
            $game = Game::firstOrCreate([
                'name' => $gameName,
                'date' => $validated['session'],
                'type' => $variant,
            ], [
                'is_active' => true,
            ]);

            $message = "Apuesta registrada (sin configuración de horario)";
        }

        $totalAmount = collect($validated['bet_details'])->sum('amount');
        $user = auth()->user();

        // Verificar saldo disponible (no congelado)
        if ($user->available_balance < $totalAmount) {
            return response()->json([
                'error' => 'Saldo disponible insuficiente para realizar la apuesta',
                'available_balance' => $user->available_balance,
                'frozen_balance' => $user->frozen_balance,
                'total_balance' => $user->wallet_balance,
                'required_amount' => $totalAmount
            ], 400);
        }

        // Descontar del saldo total
        $user->decrement('wallet_balance', $totalAmount);

        $bet = Bet::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'type' => $validated['variant'],
            'bet_details' => $validated['bet_details'],
            'session_time' => $request->input('session_time', null),
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return response()->json([
            'bet' => $bet,
            'message' => $message,
            'balance_info' => [
                'available_balance' => $user->available_balance,
                'frozen_balance' => $user->frozen_balance,
                'total_balance' => $user->wallet_balance
            ]
        ], 201);
    }

    /**
     * Calcula la siguiente sesión basada en la sesión actual
     */
    private function calculateNextSession($currentSession)
    {
        // Asumiendo que las sesiones son diarias
        $currentDate = \Carbon\Carbon::parse($currentSession);
        $nextDate = $currentDate->addDay();

        return $nextDate->format('Y-m-d');
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
