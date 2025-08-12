<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Filament\Resources\WithdrawalRequestResource\RelationManagers;
use App\Models\WithdrawalRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Extracciones';
    protected static ?string $modelLabel = 'Extracción';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('Usuario')
                    ->disabled(),
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->disabled(),
                Forms\Components\TextInput::make('card_number')
                    ->label('Número de Tarjeta')
                    ->disabled(),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Teléfono')
                    ->disabled(),
                Forms\Components\Textarea::make('note')
                    ->label('Nota')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('user.name')->label('Usuario'),
                TextColumn::make('amount')->label('Monto'),
                TextColumn::make('card_number')->label('Tarjeta'),
                TextColumn::make('phone_number')->label('Teléfono'),
                TextColumn::make('status')->label('Estado'),
                TextColumn::make('note')->label('Nota')->limit(30),
                TextColumn::make('created_at')->label('Fecha')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Aprobar')
                    ->action(fn ($record) => $record->approve())
                    ->visible(fn ($record) => $record->status === 'pending'),

                Action::make('Rechazar')
                    ->action(fn ($record) => $record->reject())
                    ->visible(fn ($record) => $record->status === 'pending'),
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
            'index' => Pages\ListWithdrawalRequests::route('/'),
            'create' => Pages\CreateWithdrawalRequest::route('/create'),
            'edit' => Pages\EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }
}
