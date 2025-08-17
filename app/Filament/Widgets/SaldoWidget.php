<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class SaldoWidget extends Widget
{
    protected static string $view = 'filament.widgets.saldo-widget';
    protected static ?int $maxColumns = 1;

    protected function getViewData(): array
    {
        $totalWallet = User::getTotalWalletBalance();
        $totalFrozen = User::getTotalFrozenBalance();
        $totalAvailable = User::getTotalAvailableBalance();

        return [
            'totalWallet' => number_format($totalWallet, 2),
            'totalFrozen' => number_format($totalFrozen, 2),
            'totalAvailable' => number_format($totalAvailable, 2),
        ];
    }
}
