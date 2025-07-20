<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'name',
        'date',
        'pick3_winning_number',
        'pick4_winning_number',
    ];

    protected static function booted()
    {
        static::updated(function ($game) {
            // Procesar apuestas pick3 si se actualizó pick3_winning_number
            if ($game->isDirty('pick3_winning_number') && $game->pick3_winning_number) {
                // Procesar apuestas pick3
                $betsPick3 = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'pick3')
                    ->where('status', 'pending')
                    ->get();

                foreach ($betsPick3 as $bet) {
                    $bet->calculatePayout($game->pick3_winning_number);
                }

                // Procesar apuestas fijo (comparando solo los dos últimos dígitos del pick3)
                $betsFijo = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'fijo')
                    ->where('status', 'pending')
                    ->get();

                $lastTwoDigits = substr($game->pick3_winning_number, -2);

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

            // Procesar apuestas pick4, corrido y parle si se actualizó pick4_winning_number
            if ($game->isDirty('pick4_winning_number') && $game->pick4_winning_number) {
                // Procesar apuestas pick4
                $betsPick4 = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'pick4')
                    ->where('status', 'pending')
                    ->get();

                foreach ($betsPick4 as $bet) {
                    $bet->calculatePayout($game->pick4_winning_number);
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
                        if (strpos($game->pick4_winning_number, $detalle['number']) !== false) {
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

                // Obtener ganadores para parle (fijo y corridos)
                $ganadores = [];
                if ($game->pick3_winning_number) {
                    $ganadores[] = substr($game->pick3_winning_number, -2);
                }
                $ganadores[] = substr($game->pick4_winning_number, 0, 2);
                $ganadores[] = substr($game->pick4_winning_number, -2);

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
