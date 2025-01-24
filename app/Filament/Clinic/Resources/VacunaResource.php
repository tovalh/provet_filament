<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\VacunaResource\Pages;
use App\Models\Vacuna;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VacunaResource extends Resource
{
    protected static ?string $model = Vacuna::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationLabel = 'Vacunas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('mascota_id')
                ->relationship('mascota', 'nombre')
                ->required()
                ->preload()
                ->searchable(),

            Forms\Components\Select::make('historial_medico_id')
                ->relationship('historialMedico', 'fecha_consulta')
                ->label('Consulta relacionada')
                ->searchable()
                ->preload()
                ->nullable(),

            Forms\Components\TextInput::make('nombre_vacuna')
                ->required()
                ->maxLength(255),

            Forms\Components\DatePicker::make('fecha_aplicacion')
                ->required()
                ->default(now()),

            Forms\Components\DatePicker::make('fecha_revacunacion'),

            Forms\Components\TextInput::make('lote')
                ->maxLength(255),

            Forms\Components\Textarea::make('observaciones')
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mascota.nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_vacuna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_aplicacion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_revacunacion')
                    ->date(),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor'),
                Tables\Columns\TextColumn::make('historialMedico.fecha_consulta')
                    ->label('Consulta')
                    ->date(),
            ])
            ->defaultSort('fecha_aplicacion', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVacunas::route('/'),
            'create' => Pages\CreateVacuna::route('/create'),
            'edit' => Pages\EditVacuna::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()
            ->where('clinic_id', $user->clinic_id ?? $user->clinic->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['clinic_id'] = auth()->user()->clinic_id ?? auth()->user()->clinic->id;
        return $data;
    }
}
