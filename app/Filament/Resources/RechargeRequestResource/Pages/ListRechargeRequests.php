<?php

namespace App\Filament\Resources\RechargeRequestResource\Pages;

use App\Filament\Resources\RechargeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRechargeRequests extends ListRecords
{
    protected static string $resource = RechargeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
