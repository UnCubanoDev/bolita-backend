<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'name',
        'type',
        'date',
        'winning_number',
        'is_active',
    ];

    protected static function booted()
    {
        static::updated(function ($game) {
            // Solo procesar si winning_number es válido y ha cambiado
            if (!$game->winning_number || !$game->isDirty('winning_number')) {
                return;
            }

            // Procesar juegos de tipo pick3
            if ($game->type === 'pick3') {
                // Procesar apuestas pick3
                $betsPick3 = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'pick3')
                    ->where('status', 'pending')
                    ->get();

                foreach ($betsPick3 as $bet) {
                    $bet->calculatePayout($game->winning_number);
                }

                // Procesar apuestas fijo (comparando solo los dos últimos dígitos)
                $betsFijo = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'fijo')
                    ->where('status', 'pending')
                    ->get();

                $lastTwoDigits = substr($game->winning_number, -2);

                foreach ($betsFijo as $bet) {
                    $totalPayout = 0;
                    foreach ($bet->bet_details as $detalle) {
                        if ($detalle['number'] === $lastTwoDigits) {
                            $payoutMultiplier = (float) \App\Models\Setting::get('payout_fijo', 50);
                            $totalPayout += $detalle['amount'] * $payoutMultiplier;
                        }
                    }
                    $bet->update([
                        'total_payout' => $totalPayout,
                        'status' => $totalPayout > 0 ? 'won' : 'lost'
                    ]);
                    if ($totalPayout > 0) {
                        $bet->user->increment('wallet_balance', $totalPayout);
                    }
                }
            }

            // Procesar juegos de tipo pick4
            if ($game->type === 'pick4') {
                // Procesar apuestas pick4
                $betsPick4 = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'pick4')
                    ->where('status', 'pending')
                    ->get();

                foreach ($betsPick4 as $bet) {
                    $bet->calculatePayout($game->winning_number);
                }

                // Procesar apuestas corrido
                $betsCorrido = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'corrido')
                    ->where('status', 'pending')
                    ->get();

                foreach ($betsCorrido as $bet) {
                    $totalPayout = 0;
                    $payoutMultiplier = (float) \App\Models\Setting::get('payout_corrido', 25);

                    foreach ($bet->bet_details as $detalle) {
                        if (strpos($game->winning_number, $detalle['number']) !== false) {
                            $totalPayout += $detalle['amount'] * $payoutMultiplier;
                        }
                    }

                    $bet->update([
                        'total_payout' => $totalPayout,
                        'status' => $totalPayout > 0 ? 'won' : 'lost'
                    ]);
                    if ($totalPayout > 0) {
                        $bet->user->increment('wallet_balance', $totalPayout);
                    }
                }

                // Obtener todos los juegos pick3 y pick4 de la misma fecha/sesión
                $games = \App\Models\Game::where('date', $game->date)
                    ->whereIn('type', ['pick3', 'pick4'])
                    ->whereNotNull('winning_number')
                    ->get();

                // Recolectar los números ganadores de fijo y corridos
                $ganadores = [];
                foreach ($games as $g) {
                    if ($g->type === 'pick3') {
                        // Fijo: dos últimos dígitos
                        $ganadores[] = substr($g->winning_number, -2);
                    } elseif ($g->type === 'pick4') {
                        // Corridos: dos primeros y dos últimos dígitos
                        $ganadores[] = substr($g->winning_number, 0, 2);
                        $ganadores[] = substr($g->winning_number, -2);
                    }
                }

                // Procesar apuestas parle
                $betsParle = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'parle')
                    ->where('status', 'pending')
                    ->get();

                foreach ($betsParle as $bet) {
                    $totalPayout = 0;
                    $payoutMultiplier = (float) \App\Models\Setting::get('payout_parle', 200);

                    foreach ($bet->bet_details as $detalle) {
                        if (strlen($detalle['number']) === 4) {
                            $parleFirst = substr($detalle['number'], 0, 2);
                            $parleLast = substr($detalle['number'], 2, 2);

                            // Verifica si ambos números están entre los ganadores
                            if (in_array($parleFirst, $ganadores) && in_array($parleLast, $ganadores)) {
                                $totalPayout += $detalle['amount'] * $payoutMultiplier;
                            }
                        }
                    }

                    $bet->update([
                        'total_payout' => $totalPayout,
                        'status' => $totalPayout > 0 ? 'won' : 'lost'
                    ]);
                    if ($totalPayout > 0) {
                        $bet->user->increment('wallet_balance', $totalPayout);
                    }
                }
            }
        });
    }

    public static function getAllowedGames(): array
    {
        return [
            ['name' => 'Georgia Lottery', 'variants' => ['pick3', 'pick4', 'fijo', 'corrido']],
            ['name' => 'Florida Lottery', 'variants' => ['pick3', 'pick4', 'fijo', 'corrido']],
            ['name' => 'New York Lottery', 'variants' => ['pick3', 'pick4', 'fijo', 'corrido']],
        ];
    }
}
