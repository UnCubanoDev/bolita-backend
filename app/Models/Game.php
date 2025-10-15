<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'id',
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
                // Procesar TODAS las apuestas pick3 (no solo las pending)
                $betsPick3 = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'pick3')
                    ->get();

                foreach ($betsPick3 as $bet) {
                    // Bonificación por referido (idempotente por apuesta)
                    $user = $bet->user;
                    if ($user && $user->referrer_code) {
                        $referrer = \App\Models\User::where('my_referral_code', $user->referrer_code)->first();
                        if ($referrer) {
                            $exists = \App\Models\ReferralBonus::where('bet_id', $bet->id)->exists();
                            if (!$exists) {
                                $bonus = $bet->total_amount * 0.05;
                                $referrer->increment('wallet_balance', $bonus);
                                \App\Models\ReferralBonus::create([
                                    'referrer_id' => $referrer->id,
                                    'referred_user_id' => $user->id,
                                    'bonus_amount' => $bonus,
                                    'bet_id' => $bet->id,
                                    'credited_at' => now(),
                                ]);
                            }
                        }
                    }
                    $oldPayout = $bet->total_payout;
                    $bet->calculatePayout($game->pick3_winning_number);

                    // Si el payout anterior era > 0 y ahora es 0, decrementar la wallet
                    if ($oldPayout > 0 && $bet->total_payout == 0) {
                        $bet->user->decrement('wallet_balance', $oldPayout);
                    }
                }

                // Procesar TODAS las apuestas fijo (no solo las pending)
                $betsFijo = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'fijo')
                    ->get();

                $lastTwoDigits = substr($game->pick3_winning_number, -2);

                foreach ($betsFijo as $bet) {
                    $oldPayout = $bet->total_payout;
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

                    // Si el payout anterior era > 0 y ahora es 0, decrementar la wallet
                    if ($oldPayout > 0 && $totalPayout == 0) {
                        $bet->user->decrement('wallet_balance', $oldPayout);
                    } elseif ($totalPayout > 0 && $oldPayout == 0) {
                        // Si el payout anterior era 0 y ahora es > 0, incrementar la wallet
                        $bet->user->increment('wallet_balance', $totalPayout);
                    } elseif ($oldPayout > 0 && $totalPayout > 0 && $oldPayout != $totalPayout) {
                        // Si el payout cambió pero sigue siendo > 0, ajustar la diferencia
                        $difference = $totalPayout - $oldPayout;
                        if ($difference > 0) {
                            $bet->user->increment('wallet_balance', $difference);
                        } else {
                            $bet->user->decrement('wallet_balance', abs($difference));
                        }
                    }
                }
            }

            // Procesar apuestas pick4, corrido y parle si se actualizó pick4_winning_number
            if ($game->isDirty('pick4_winning_number') && $game->pick4_winning_number) {
                // Procesar TODAS las apuestas pick4 (no solo las pending)
                $betsPick4 = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'pick4')
                    ->get();

                foreach ($betsPick4 as $bet) {
                    // Bonificación por referido (idempotente por apuesta)
                    $user = $bet->user;
                    if ($user && $user->referrer_code) {
                        $referrer = \App\Models\User::where('my_referral_code', $user->referrer_code)->first();
                        if ($referrer) {
                            $exists = \App\Models\ReferralBonus::where('bet_id', $bet->id)->exists();
                            if (!$exists) {
                                $bonus = $bet->total_amount * 0.05;
                                $referrer->increment('wallet_balance', $bonus);
                                \App\Models\ReferralBonus::create([
                                    'referrer_id' => $referrer->id,
                                    'referred_user_id' => $user->id,
                                    'bonus_amount' => $bonus,
                                    'bet_id' => $bet->id,
                                    'credited_at' => now(),
                                ]);
                            }
                        }
                    }
                    $oldPayout = $bet->total_payout;
                    $bet->calculatePayout($game->pick4_winning_number);

                    // Si el payout anterior era > 0 y ahora es 0, decrementar la wallet
                    if ($oldPayout > 0 && $bet->total_payout == 0) {
                        $bet->user->decrement('wallet_balance', $oldPayout);
                    }
                }

                // Procesar TODAS las apuestas corrido (no solo las pending)
                $betsCorrido = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'corrido')
                    ->get();

                foreach ($betsCorrido as $bet) {
                    $oldPayout = $bet->total_payout;
                    $totalPayout = 0;
                    $payoutMultiplier = (float) \App\Models\Setting::get('payout_corrido', 25);

                    // Dividir el número ganador en pares de 2 dígitos
                    $winningPairs = [
                        substr($game->pick4_winning_number, 0, 2), // primeros 2 dígitos (12)
                        substr($game->pick4_winning_number, 2, 2)  // últimos 2 dígitos (34)
                    ];

                    foreach ($bet->bet_details as $detalle) {
                        // Verificar si el número apostado coincide exactamente con alguno de los pares ganadores
                        if (in_array($detalle['number'], $winningPairs)) {
                            $totalPayout += $detalle['amount'] * $payoutMultiplier;
                        }
                    }

                    $bet->update([
                        'total_payout' => $totalPayout,
                        'status' => $totalPayout > 0 ? 'won' : 'lost'
                    ]);

                    // Si el payout anterior era > 0 y ahora es 0, decrementar la wallet
                    if ($oldPayout > 0 && $totalPayout == 0) {
                        $bet->user->decrement('wallet_balance', $oldPayout);
                    } elseif ($totalPayout > 0 && $oldPayout == 0) {
                        // Si el payout anterior era 0 y ahora es > 0, incrementar la wallet
                        $bet->user->increment('wallet_balance', $totalPayout);
                    } elseif ($oldPayout > 0 && $totalPayout > 0 && $oldPayout != $totalPayout) {
                        // Si el payout cambió pero sigue siendo > 0, ajustar la diferencia
                        $difference = $totalPayout - $oldPayout;
                        if ($difference > 0) {
                            $bet->user->increment('wallet_balance', $difference);
                        } else {
                            $bet->user->decrement('wallet_balance', abs($difference));
                        }
                    }
                }

                // Obtener ganadores para parle (fijo y corridos)
                $ganadores = [];
                if ($game->pick3_winning_number) {
                    $ganadores[] = substr($game->pick3_winning_number, -2);
                }
                $ganadores[] = substr($game->pick4_winning_number, 0, 2);
                $ganadores[] = substr($game->pick4_winning_number, -2);

                // Procesar TODAS las apuestas parle (no solo las pending)
                $betsParle = \App\Models\Bet::where('game_id', $game->id)
                    ->where('type', 'parle')
                    ->get();

                foreach ($betsParle as $bet) {
                    $oldPayout = $bet->total_payout;
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

                    // Si el payout anterior era > 0 y ahora es 0, decrementar la wallet
                    if ($oldPayout > 0 && $totalPayout == 0) {
                        $bet->user->decrement('wallet_balance', $oldPayout);
                    } elseif ($totalPayout > 0 && $oldPayout == 0) {
                        // Si el payout anterior era 0 y ahora es > 0, incrementar la wallet
                        $bet->user->increment('wallet_balance', $totalPayout);
                    } elseif ($oldPayout > 0 && $totalPayout > 0 && $oldPayout != $totalPayout) {
                        // Si el payout cambió pero sigue siendo > 0, ajustar la diferencia
                        $difference = $totalPayout - $oldPayout;
                        if ($difference > 0) {
                            $bet->user->increment('wallet_balance', $difference);
                        } else {
                            $bet->user->decrement('wallet_balance', abs($difference));
                        }
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

    public function bets()
    {
        return $this->hasMany(\App\Models\Bet::class, 'game_id');
    }
}
