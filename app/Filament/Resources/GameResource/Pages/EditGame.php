<?php

namespace App\Filament\Resources\GameResource\Pages;

use App\Filament\Resources\GameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGame extends EditRecord
{
    protected static string $resource = GameResource::class;

    protected function afterSave(): void
    {
        $game = $this->record;

        // 🔥 Forzar evento updated aunque no haya cambios
        $game->fill($game->getAttributes());
        $game->saveQuietly(); // evita dobles eventos

        $game->touch(); // asegura que se dispare "updated"
        $game->refresh();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
