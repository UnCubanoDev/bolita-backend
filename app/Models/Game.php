<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    // /**
    //  * Obtiene los resultados asociados al juego.
    //  */
    // public function results()
    // {
    //     return $this->hasMany(GameResult::class);
    // }

    // Agrega 'name' a la propiedad fillable
    protected $fillable = [
        'name',
        'type',
        'date',
        'winning_number',
        'is_active',
    ];

    protected static function booted()
    {
        static::created(function ($game) {
            if (!$game->winning_number || $game->type !== 'pick3') {
                return;
            }

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
                        // Puedes definir el multiplicador para fijo aquí
                        $totalPayout += $detalle['amount'] * 50; // Ejemplo: multiplicador 50
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
        });
    }

    public static function getAllowedGames(): array
    {
        return [
            ['name' => 'Georgia Lottery', 'variants' => ['pick3', 'pick4']],
            ['name' => 'Florida Lottery', 'variants' => ['pick3', 'pick4']],
            ['name' => 'New York Lottery', 'variants' => ['pick3', 'pick4']],
        ];
    }
}
