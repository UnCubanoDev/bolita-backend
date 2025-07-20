<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Filament\Resources\GameResource\RelationManagers;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('name')->options([
                    'Georgia' => 'Georgia',
                    'New York' => 'New York',
                    'Florida' => 'Florida',
                ])->required(),
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\TextInput::make('pick3_winning_number')
                    ->label('Pick 3 - Número ganador')
                    ->maxLength(3)
                    ->nullable(),
                Forms\Components\TextInput::make('pick4_winning_number')
                    ->label('Pick 4 - Número ganador')
                    ->maxLength(4)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('pick3_winning_number')->label('Pick 3'),
                Tables\Columns\TextColumn::make('pick4_winning_number')->label('Pick 4'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}
