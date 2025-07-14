<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralBonusResource\Pages;
use App\Filament\Resources\ReferralBonusResource\RelationManagers;
use App\Models\ReferralBonus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferralBonusResource extends Resource
{
    protected static ?string $model = ReferralBonus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('referrer_id')->relationship('referrer', 'name')->required(),
                Forms\Components\Select::make('referred_user_id')->relationship('referredUser', 'name')->required(),
                Forms\Components\TextInput::make('bonus_amount')->numeric()->required(),
                Forms\Components\DateTimePicker::make('credited_at')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referrer.name')->label('Referente'),
                Tables\Columns\TextColumn::make('referredUser.name')->label('Referido'),
                Tables\Columns\TextColumn::make('bonus_amount')->money('CUP'),
                Tables\Columns\TextColumn::make('credited_at')->since(),
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
            'index' => Pages\ListReferralBonuses::route('/'),
            'create' => Pages\CreateReferralBonus::route('/create'),
            'edit' => Pages\EditReferralBonus::route('/{record}/edit'),
        ];
    }
}
