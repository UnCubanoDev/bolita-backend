<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('my_referral_code')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('referrer_code'),
                Forms\Components\TextInput::make('wallet_balance')->numeric()->default(0),
                Forms\Components\TextInput::make('available_balance')->numeric()->default(0),
                Forms\Components\TextInput::make('password')->password()->required(fn ($record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nombre'),
                // Tables\Columns\TextColumn::make('email')->searchable(),
                // Tables\Columns\TextColumn::make('phone'),
                // Tables\Columns\TextColumn::make('my_referral_code')->label('Código de Referido'),
                Tables\Columns\TextColumn::make('referrer_code')->label('Código del Referente'),
                Tables\Columns\TextColumn::make('wallet_balance')->money('CUP')->sortable()->label('Saldo'),
                Tables\Columns\TextColumn::make('available_balance')->money('CUP')->sortable()->label('Saldo Disponible'),
                Tables\Columns\TextColumn::make('frozen_balance')->money('CUP')->sortable()->label('Saldo Congelado'),
            ])
            ->filters([
                Tables\Filters\Filter::make('with_balance')
                    ->query(fn ($query) => $query->where('wallet_balance', '>', 0)),
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
            // RelationManagers\BetRelationManager::class,
            // RelationManagers\ReferralBonusRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
