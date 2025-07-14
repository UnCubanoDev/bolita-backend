<?php

namespace App\Filament\Resources\ReferralBonusResource\Pages;

use App\Filament\Resources\ReferralBonusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReferralBonus extends EditRecord
{
    protected static string $resource = ReferralBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
