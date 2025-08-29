<?php

namespace App\Filament\Widgets;

use App\Models\Bet;
use App\Models\Game;
use Filament\Widgets\Widget;

class WinnersWidget extends Widget
{
    protected static string $view = 'filament.widgets.winners-widget';
    protected static ?int $maxColumns = 2;
    protected static ?int $sort = 40;

    public function getColumnSpan(): int|string|array
    {
        return 2;
    }

    public ?string $selectedGameName = null;
    public ?string $selectedDate = null; // formato YYYY-MM-DD
    public ?string $selectedVariant = null;

    public array $gameNames = [];
    public array $dates = [];
    public array $variants = ['fijo', 'pick3', 'pick4', 'corrido', 'parle'];

    public function mount(): void
    {
        $this->gameNames = Game::query()
            ->select('name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        $this->dates = Game::query()
            ->select('date')
            ->distinct()
            ->orderByDesc('date')
            ->pluck('date')
            ->toArray();

        // Defaults: último juego con resultados
        if (!$this->selectedGameName || !$this->selectedDate) {
            $lastWithResults = Game::query()
                ->where(function ($q) {
                    $q->whereNotNull('pick3_winning_number')
                      ->orWhereNotNull('pick4_winning_number');
                })
                ->orderByDesc('updated_at')
                ->first();

            if ($lastWithResults) {
                $this->selectedGameName = $this->selectedGameName ?? $lastWithResults->name;
                $this->selectedDate = $this->selectedDate ?? $lastWithResults->date;
            }
        }
    }

    public function updatedSelectedGameName(): void {}
    public function updatedSelectedDate(): void {}
    public function updatedSelectedVariant(): void {}

    protected function getViewData(): array
    {
        $betsQuery = Bet::query()
            ->with(['user', 'game'])
            ->where('status', 'won');

        if ($this->selectedVariant) {
            $betsQuery->where('type', $this->selectedVariant);
        }

        // Filtrar por juego y día a través de la relación con Game
        $betsQuery->whereHas('game', function ($q) {
            if ($this->selectedGameName) {
                $q->where('name', $this->selectedGameName);
            }
            if ($this->selectedDate) {
                $q->where('date', $this->selectedDate);
            }
        });

        $bets = $betsQuery->orderByDesc('updated_at')->get();

        // Armar filas con número ganador adecuado según tipo y juego de cada apuesta
        $rows = $bets->map(function (Bet $bet) {
            $game = $bet->game;
            $winningUsed = null;

            switch ($bet->type) {
                case 'pick3':
                    $winningUsed = $game?->pick3_winning_number;
                    break;
                case 'fijo':
                    $winningUsed = $game?->pick3_winning_number
                        ? substr($game->pick3_winning_number, -2)
                        : null;
                    break;
                case 'pick4':
                case 'corrido':
                case 'parle':
                    $winningUsed = $game?->pick4_winning_number;
                    break;
                default:
                    $winningUsed = $game?->pick4_winning_number ?? $game?->pick3_winning_number;
                    break;
            }

            return [
                'user' => $bet->user?->name ?? ('Usuario #'.$bet->user_id),
                'lotto' => $bet->lotto ?? $game?->name,
                'date' => $game?->date,
                'type' => $bet->type,
                'numbers' => collect($bet->bet_details)
                    ->map(fn ($d) => "{$d['number']} (" . number_format((float)$d['amount'], 2) . ")")
                    ->implode(', '),
                'winning_number' => $winningUsed,
                'payout' => number_format((float)$bet->total_payout, 2),
            ];
        });

        // Juego de contexto (cabecera) si hay filtros; si no, toma el más reciente con resultados
        $contextGame = Game::query()
            ->when($this->selectedGameName, fn ($q) => $q->where('name', $this->selectedGameName))
            ->when($this->selectedDate, fn ($q) => $q->where('date', $this->selectedDate))
            ->orderByDesc('updated_at')
            ->first();

        return [
            'game' => $contextGame,
            'bets' => $rows,
            'gameNames' => $this->gameNames,
            'dates' => $this->dates,
            'variants' => $this->variants,
            'selectedGameName' => $this->selectedGameName,
            'selectedDate' => $this->selectedDate,
            'selectedVariant' => $this->selectedVariant,
        ];
    }
}
