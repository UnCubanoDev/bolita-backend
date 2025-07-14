<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BetResource\Pages;
use App\Filament\Resources\BetResource\RelationManagers;
use App\Models\Bet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class BetResource extends Resource
{
    protected static ?string $model = Bet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
                Forms\Components\Select::make('game')->options([
                    'Georgia' => 'Georgia',
                    'New York' => 'New York',
                    'Florida' => 'Florida',
                ])->required(),
                Forms\Components\Select::make('type')->options([
                    'pick3' => 'Pick 3',
                    'pick4' => 'Pick 4',
                    'fijo' => 'Fijo',
                    'corrido' => 'Corrido',
                ])->required()
                ->live()
                ->reactive()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    // Limpiar los detalles de la apuesta al cambiar el tipo
                    $set('bet_details', []);
                    // Forzar la actualización de las reglas en el campo 'number'
                    $set('bet_details.0.number', null);
                }),
                Forms\Components\Select::make('session_time')->options([
                    'morning' => 'Mañana',
                    'evening' => 'Tarde'
                ])->required(),
                Forms\Components\Repeater::make('bet_details')
                    ->label('Detalles de la apuesta')
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label('Número')
                            ->numeric()
                            ->required()
                            ->minLength(2)
                            ->maxLength(4),

                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->placeholder('Ej: 100'),
                    ])
                    ->minItems(1)
                    ->columns(2)
                    ->addActionLabel('Agregar apuesta')
                    ->required()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $betDetails = $get('bet_details');
                        $totalAmount = collect($betDetails)->sum('amount');
                        $set('total_amount', $totalAmount);
                    }),
                Forms\Components\TextInput::make('total_amount')
                    ->label('Monto Total')
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Usuario'),
                Tables\Columns\TextColumn::make('game')->label('Juego'),
                Tables\Columns\TextColumn::make('type')->label('Tipo')->sortable(),
                Tables\Columns\TextColumn::make('session_time')->label('Sesión')->formatStateUsing(fn (string $state): string =>
                    $state === 'morning' ? 'Mañana' : 'Tarde'
                ),
                Tables\Columns\TextColumn::make('bet_details')
                    ->label('Números')
                    ->formatStateUsing(function ($state, $record) {
                        if (isset($record->bet_details) && is_array($record->bet_details)) {
                            $numbers = collect($record->bet_details)->pluck('number')->implode(', ');
                            return $numbers;
                        }
                        return 'No hay datos';
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Monto Total')
                    ->money('CUP'),
                Tables\Columns\TextColumn::make('payout')->label('Pagado')->money('CUP'),
                Tables\Columns\BadgeColumn::make('status')->label('Estado')->colors([
                    'primary' => 'pending',
                    'success' => 'won',
                    'danger' => 'lost',
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'won' => 'Ganada',
                        'lost' => 'Perdida',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBets::route('/'),
            'create' => Pages\CreateBet::route('/create'),
            'edit' => Pages\EditBet::route('/{record}/edit'),
        ];
    }
}
