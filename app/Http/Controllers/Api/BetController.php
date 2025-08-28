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
                // 🚫 Fuera de horarios → NO se crea la apuesta, solo mensaje
                if ($currentTime->gt($eveningEnd)) {
                    // Después del horario de tarde → próxima mañana
                    $nextSession = $morningStart->copy()->addDay()->format('H:i');
                    return response()->json([
                        'message' => "No puede apostar en este momento. El próximo horario disponible es mañana a las {$nextSession}"
                    ], 400);
                } elseif ($currentTime->lt($morningStart)) {
                    // Antes del horario de mañana
                    return response()->json([
                        'message' => "No puede apostar en este momento. El próximo horario disponible es hoy en la mañana: {$morningStart->format('H:i')} - {$morningEnd->format('H:i')}"
                    ], 400);
                } else {
                    // Gap entre mañana y tarde
                    return response()->json([
                        'message' => "No puede apostar en este momento. El próximo horario disponible es en la tarde: {$eveningStart->format('H:i')} - {$eveningEnd->format('H:i')}"
                    ], 400);
                }
            }

            // Dentro del horario de apuestas (mañana o tarde)
            $game = Game::firstOrCreate([
                'name' => $gameName,
                'date' => $validated['session']
            ]);

            $message = "Apuesta registrada para la sesión actual ({$period})";
        } else {
            // Si no hay configuración de horario, usar la sesión actual
            $game = Game::firstOrCreate([
                'name' => $gameName,
                'date' => $validated['session']
            ]);

            $message = "Apuesta registrada (sin configuración de horario)";
        }

        $totalAmount = collect($validated['bet_details'])->sum('amount');
        $user = auth()->user();

        // 🔎 Validar límite por número y tipo de apuesta
        foreach ($validated['bet_details'] as $detail) {
            $number = $detail['number'];
            $amount = $detail['amount'];
            $variant = $validated['variant'];

            // Obtener límite desde settings
            switch ($variant) {
                case 'fijo':
                    $limit = Setting::where('key', 'limit_fijo')->value('value');
                    break;
                case 'pick3':
                    $limit = Setting::where('key', 'limit_pick3')->value('value');
                    break;
                case 'pick4':
                    $limit = Setting::where('key', 'limit_pick4')->value('value');
                    break;
                case 'corrido':
                    $limit = Setting::where('key', 'limit_corrido')->value('value');
                    break;
                case 'parle':
                    $limit = Setting::where('key', 'limit_parle')->value('value');
                    break;
                default:
                    $limit = null;
            }

            if ($limit) {
                // Sumar monto total ya apostado en este número para este tipo
                $currentBets = Bet::where('game_id', $game->id)
                    ->where('type', $variant)
                    ->whereJsonContains('bet_details', [['number' => $number]])
                    ->get()
                    ->flatMap(function ($bet) {
                        return collect($bet->bet_details)->pluck('amount', 'number');
                    })
                    ->get($number, 0);

                if ($currentBets + $amount > $limit) {
                    return response()->json([
                        'message' => "El número {$number} en la modalidad {$variant} ya alcanzó el límite de {$limit}.",
                        'apostado_actual' => $currentBets,
                        'intento' => $amount
                    ], 400);
                }
            }
        }

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
            'lotto' => $gameName,
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
