<?php

namespace App\Filament\Widgets;

use App\Models\Bet;
use App\Models\Game;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class PlatformStatsWidget extends Widget
{
	protected static string $view = 'filament.widgets.platform-stats-widget';
	protected static ?int $maxColumns = 2;
    protected static ?int $sort = 30;

	public function getColumnSpan(): int|string|array
	{
		return 2;
	}

	protected function getViewData(): array
	{
		$today = Carbon::now('America/Havana')->toDateString();

		$collected = (float) \App\Models\Bet::whereHas('game', fn($q) => $q->where('date', $today))
			->sum('total_amount');

		$paid = (float) \App\Models\Bet::whereHas('game', fn($q) => $q->where('date', $today))
			->sum('total_payout');

		$profit = $collected - $paid;

		return [
			'collected' => number_format($collected, 2),
			'paid' => number_format($paid, 2),
			'profit' => number_format($profit, 2),
			'profit_raw' => $profit,
		];
	}
}
