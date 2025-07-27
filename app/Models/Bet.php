<?php

namespace App\Models;

use App\Services\BetValidationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Setting;

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

            // Determinar la sesión actual
            $now = now('America/Havana');
            $currentTime = $now->format('H:i');

            $morningEnd = Setting::get('morning_session_end', '11:45');
            $eveningEnd = Setting::get('evening_session_end', '20:45');

            // Asignar la sesión correspondiente
            if ($currentTime <= $morningEnd) {
                $bet->session_time = 'morning';
            } elseif ($currentTime <= $eveningEnd) {
                $bet->session_time = 'evening';
            } else {
                throw new \Exception(
                    "No se pueden realizar apuestas en este momento. " .
                    $validationService->getNextValidTime()
                );
            }

            // Validar que se puede realizar la apuesta
            if (!$validationService->canPlaceBet($bet->type, $bet->session_time)) {
                throw new \Exception(
                    "No se pueden realizar apuestas para la sesión {$bet->session_time} en este momento. " .
                    $validationService->getNextValidTime()
                );
            }

            // Calcular el monto total sumando los montos de bet_details
            if (isset($bet->bet_details) && is_array($bet->bet_details)) {
                $bet->total_amount = collect($bet->bet_details)->sum('amount');
            } else {
                $bet->total_amount = 0; // O manejar el caso en que bet_details no esté definido
            }
            // --- BONO AL REFERENTE ---
                $referrerCode = $bet->user->referrer_code;
                if ($referrerCode) {
                    $referrer = \App\Models\User::where('my_referral_code', $referrerCode)->first();
                    if ($referrer) {
                        $bonus = $bet->$total_amount * 0.05;
                        $referrer->increment('wallet_balance', $bonus);

                        // (Opcional) Registrar el bono
                        \App\Models\ReferralBonus::create([
                            'referrer_id' => $referrer->id,
                            'referred_user_id' => $bet->user->id,
                            'bonus_amount' => $bonus,
                            'credited_at' => now(),
                        ]);
                    }
                }
        });
    }

    public function calculatePayout(string $winningNumber): void
    {
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

        if ($totalPayout > 0) {
            $bet->user->increment('wallet_balance', $totalPayout);
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
