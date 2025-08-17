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
        // Asegurarse de obtener valores numéricos
        $totalWallet = (float)User::getTotalWalletBalance();
        $totalFrozen = (float)User::getTotalFrozenBalance();
        $totalAvailable = (float)User::getTotalAvailableBalance();

        // Formatear solo si los valores son numéricos
        return [
            'totalWallet' => is_numeric($totalWallet) ? number_format($totalWallet, 2) : '0.00',
            'totalFrozen' => is_numeric($totalFrozen) ? number_format($totalFrozen, 2) : '0.00',
            'totalAvailable' => is_numeric($totalAvailable) ? number_format($totalAvailable, 2) : '0.00',
        ];
    }
}
