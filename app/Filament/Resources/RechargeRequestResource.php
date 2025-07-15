<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RechargeRequestResource\Pages;
use App\Filament\Resources\RechargeRequestResource\RelationManagers;
use App\Models\RechargeRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RechargeRequestResource extends Resource
{
    protected static ?string $model = RechargeRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->directory('recharge_images')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'rejected' => 'Rechazada',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Usuario'),
                Tables\Columns\TextColumn::make('amount')->money('CUP')->label('Monto'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de solicitud')->dateTime(),
                Tables\Columns\ImageColumn::make('image_path')->label('Imagen')->disk('public'), // Columna para mostrar la imagen
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('viewImage') // Acción para ver la imagen
                    ->icon('heroicon-o-eye')
                    ->modalContent(function (RechargeRequest $record) {
                        return view('filament.pages.view-image', ['imagePath' => $record->image_path]);
                    })
                    ->modalHeading('Ver Imagen')
                    ->modalWidth('lg')
                    ->modalCloseButton(false),
                Tables\Actions\EditAction::make(), // Mantén la acción de edición si es necesaria
                Tables\Actions\Action::make('approve')
                    ->label('Aprobar')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function (RechargeRequest $record) {
                        $record->update(['status' => 'approved']);
                        $record->user->increment('wallet_balance', $record->amount);
                    })
                    ->visible(fn (RechargeRequest $record): bool => $record->status === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->label('Rechazar')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(function (RechargeRequest $record) {
                        $record->update(['status' => 'rejected']);
                    })
                    ->visible(fn (RechargeRequest $record): bool => $record->status === 'pending'),
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
            'index' => Pages\ListRechargeRequests::route('/'),
            'create' => Pages\CreateRechargeRequest::route('/create'),
            'edit' => Pages\EditRechargeRequest::route('/{record}/edit'),
        ];
    }
}
