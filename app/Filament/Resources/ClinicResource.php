<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicResource\Pages;
use App\Filament\Resources\ClinicResource\RelationManagers;
use App\Models\Clinic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Hidden::make('admin_id')
                    ->default(auth()->id()),

                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la Clínica')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Dirección')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true),
            ]);
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Si el usuario ya tiene una clínica, mostramos "1"
        return auth()->user()->hasClinic() ? '1' : '0';
    }

    public static function canCreate(): bool
    {
        return !auth()->user()->hasClinic();
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('admin_id', auth()->id());
    }
}
