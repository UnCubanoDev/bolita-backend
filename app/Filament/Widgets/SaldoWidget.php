<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class SaldoWidget extends Widget
{
    protected static string $view = 'filament.widgets.saldo-widget';
    protected static ?int $maxColumns = 1;

    public float $totalWallet;
    public float $totalFrozen;
    public float $totalAvailable;

    protected function getViewData(): array
    {
        return [
            'totalWallet' => User::getTotalWalletBalance(),
            'totalFrozen' => User::getTotalFrozenBalance(),
            'totalAvailable' => User::getTotalAvailableBalance(),
        ];
    }
}
