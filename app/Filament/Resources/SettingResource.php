<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $modelLabel = 'Configuración';

    protected static ?string $pluralModelLabel = 'Configuraciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->visible(fn (Forms\Get $get) => $get('type') !== 'time'),
                Forms\Components\TimePicker::make('value')
                    ->required()
                    ->visible(fn (Forms\Get $get) => $get('type') === 'time'),
                Forms\Components\Select::make('type')
                    ->options([
                        'string' => 'Texto',
                        'time' => 'Hora',
                        'boolean' => 'Booleano',
                        'number' => 'Número',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('label')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->nullable(),
                Forms\Components\Select::make('group')
                    ->options([
                        'general' => 'General',
                        'betting' => 'Apuestas',
                        'system' => 'Sistema',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('group'),
                Tables\Columns\TextColumn::make('label'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'betting' => 'Apuestas',
                        'system' => 'Sistema',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(), // This line is commented out as per the edit hint
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
            'index' => Pages\ListSettings::route('/'),
            // Elimina la ruta de creación:
            // 'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
