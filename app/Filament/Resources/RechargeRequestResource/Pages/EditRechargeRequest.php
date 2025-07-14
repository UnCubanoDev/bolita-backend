<?php

namespace App\Filament\Resources\RechargeRequestResource\Pages;

use App\Filament\Resources\RechargeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRechargeRequest extends EditRecord
{
    protected static string $resource = RechargeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
