<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;

class LoteriasWidget extends Widget
{
    protected static string $view = 'filament.widgets.loterias-widget';
    protected static ?int $maxColumns = 1;

    public array $loterias = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $response = Http::get('https://tuboliteros.com/api/loterias/?format=json');
        $allLoterias = $response->successful() ? $response->json() : [];

        // Filtrar las loterías para incluir solo Georgia, Nueva York y Florida
        $this->loterias = array_filter($allLoterias, function ($loteria) {
            return in_array($loteria['slug'], ['georgia', 'new-york', 'florida']);
        });
    }

    public function render(): \Illuminate\View\View
    {
        $response = Http::get('https://tuboliteros.com/api/loterias/?format=json');
        $allLoterias = $response->successful() ? $response->json() : [];

        // Filtrar las loterías para incluir solo Georgia, Nueva York y Florida
        $loterias = array_filter($allLoterias, function ($loteria) {
            return in_array($loteria['slug'], ['georgia', 'new-york', 'florida']);
        });

        return view(static::$view, [
            'loterias' => $loterias,
        ]);
    }
}

