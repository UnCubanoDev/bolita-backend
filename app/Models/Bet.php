<?php

namespace App\Models;

use App\Services\BetValidationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Setting;
use App\Exceptions\BettingTimeException;

class Bet extends Model
{
    protected $fillable = [
        'user_id',
        'game',
        'type',
        'session_time',
        'bet_details',
        'total_amount',
        'total_payout',
        'status',
        'game_id',
        'lotto'
    ];

    protected $casts = [
        'bet_details' => 'array',
        'total_amount' => 'decimal:2',
        'total_payout' => 'decimal:2'
    ];

    protected static function booted()
    {
        static::creating(function ($bet) {
            $validationService = new BetValidationService();

            // Determinar la sesión actual según hora de Habana
            $now = now('America/Havana');
            $currentTime = $now->format('H:i');

            $morningEnd = Setting::get('morning_session_end', '11:45');
            $noonEnd = Setting::get('noon_session_end', '14:45');  // Ajustado: añadimos mediodía
            $eveningEnd = Setting::get('evening_session_end', '20:45');

            // Asignar sesión según horario actual
            if ($currentTime < $morningEnd) {
                $bet->session_time = 'morning';
            } elseif ($currentTime < $noonEnd) {
                $bet->session_time = 'noon';
            } elseif ($currentTime < $eveningEnd) {
                $bet->session_time = 'evening';
            } else {
                // Determinar próxima sesión usando el servicio
                $nextSession = $validationService->getNextValidTime();

                if ($nextSession <= $morningEnd) {
                    $bet->session_time = 'morning';
                } elseif ($nextSession <= $noonEnd) {
                    $bet->session_time = 'noon';
                } elseif ($nextSession <= $eveningEnd) {
                    $bet->session_time = 'evening';
                }
            }

            // Calcular monto total
            $bet->total_amount = isset($bet->bet_details) && is_array($bet->bet_details)
                ? collect($bet->bet_details)->sum('amount')
                : 0;

            // Bonificación por referido
            if ($bet->user) {
                $referrerCode = $bet->user->referrer_code;

                if ($referrerCode) {
                    $referrer = \App\Models\User::where('my_referral_code', $referrerCode)->first();

                    if ($referrer) {
                        $bonus = $bet->total_amount * 0.05;
                        $referrer->increment('wallet_balance', $bonus);

                        // Registrar el bono
                        \App\Models\ReferralBonus::create([
                            'referrer_id' => $referrer->id,
                            'referred_user_id' => $bet->user->id,
                            'bonus_amount' => $bonus,
                            'credited_at' => now(),
                        ]);
                    }
                }
            }
        });
    }


    public function calculatePayout(string $winningNumber): void
    {
        $oldPayout = $this->total_payout;
        $totalPayout = 0;

        if ($this->type === 'pick3') {
            $payoutMultiplier = (float) Setting::get('payout_pick3', 500);
            foreach ($this->bet_details as $bet) {
                if ($bet['number'] === $winningNumber) {
                    $totalPayout += $bet['amount'] * $payoutMultiplier;
                }
            }
        } elseif ($this->type === 'pick4') {
            $payoutMultiplier = (float) Setting::get('payout_pick4', 5000);
            foreach ($this->bet_details as $bet) {
                if ($bet['number'] === $winningNumber) {
                    $totalPayout += $bet['amount'] * $payoutMultiplier;
                }
            }
        } elseif ($this->type === 'fijo') {
            $payoutMultiplier = (float) Setting::get('payout_fijo', 50);
            $lastTwoDigits = substr($winningNumber, -2);
            foreach ($this->bet_details as $bet) {
                if ($bet['number'] === $lastTwoDigits) {
                    $totalPayout += $bet['amount'] * $payoutMultiplier;
                }
            }
        } elseif ($this->type === 'parle') {
            $payoutMultiplier = (float) Setting::get('payout_parle', 200);
            // Tomar los dos primeros y dos últimos dígitos del pick4
            if (strlen($winningNumber) === 4) {
                $firstTwo = substr($winningNumber, 0, 2);
                $lastTwo = substr($winningNumber, -2);
                $combo1 = $firstTwo . $lastTwo;
                $combo2 = $lastTwo . $firstTwo;
                foreach ($this->bet_details as $bet) {
                    if ($bet['number'] === $combo1 || $bet['number'] === $combo2) {
                        $totalPayout += $bet['amount'] * $payoutMultiplier;
                    }
                }
            }
        } elseif ($this->type === 'corrido') {
            $payoutMultiplier = (float) Setting::get('payout_corrido', 20); // Ajusta el payout si es necesario
            if (strlen($winningNumber) === 4) {
                $firstTwo = substr($winningNumber, 0, 2);
                $lastTwo = substr($winningNumber, -2);

                $foundFirst = false;
                $foundLast = false;
                $amountFirst = 0;
                $amountLast = 0;

                foreach ($this->bet_details as $bet) {
                    if ($bet['number'] === $firstTwo) {
                        $foundFirst = true;
                        $amountFirst += $bet['amount'];
                    }
                    if ($bet['number'] === $lastTwo) {
                        $foundLast = true;
                        $amountLast += $bet['amount'];
                    }
                }

                if ($foundFirst && $foundLast) {
                    // Si ambos existen, se multiplica por 2
                    $totalPayout += ($amountFirst + $amountLast) * $payoutMultiplier * 2;
                } elseif ($foundFirst) {
                    $totalPayout += $amountFirst * $payoutMultiplier;
                } elseif ($foundLast) {
                    $totalPayout += $amountLast * $payoutMultiplier;
                }
            }
        } else {
            $totalPayout = 0;
        }

        $this->update([
            'total_payout' => $totalPayout,
            'status' => $totalPayout > 0 ? 'won' : 'lost'
        ]);

        // Si el payout anterior era > 0 y ahora es 0, decrementar la wallet
        if ($oldPayout > 0 && $totalPayout == 0) {
            $this->user->decrement('wallet_balance', $oldPayout);
        } elseif ($totalPayout > 0) {
            $this->user->increment('wallet_balance', $totalPayout);
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
