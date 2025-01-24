<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\HistorialMedicoResource\Pages;
use App\Models\HistorialMedico;
use App\Models\Cliente;
use App\Models\Mascota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HistorialMedicoResource extends Resource
{
    protected static ?string $model = HistorialMedico::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Historiales Médicos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información básica')
                ->schema([
                    Forms\Components\Select::make('cliente_id')
                        ->options(function($record) {
                            if ($record) {
                                $cliente = $record->mascota->cliente;
                                return [$cliente->id => "{$cliente->nombre} {$cliente->apellido}"];
                            }
                            return Cliente::query()
                                ->where('clinic_id', auth()->user()->clinic_id)
                                ->get()
                                ->mapWithKeys(fn($c) => [$c->id => "{$c->nombre} {$c->apellido}"]);
                        })
                        ->default(fn($record) => $record?->mascota?->cliente_id)
                        ->label('Cliente')
                        ->live()
                        ->searchable(),

                    Forms\Components\Select::make('mascota_id')
                        ->options(function($record, Forms\Get $get) {
                            $clienteId = $get('cliente_id');
                            if (!$clienteId && $record) {
                                $clienteId = $record->mascota->cliente_id;
                            }
                            return Mascota::where('cliente_id', $clienteId)
                                ->get()
                                ->mapWithKeys(fn($m) => [$m->id => $m->nombre]);
                        })
                        ->default(fn($record) => $record?->mascota_id)
                        ->label('Mascota')
                        ->searchable(),

                    Forms\Components\DatePicker::make('fecha_consulta')
                        ->required()
                        ->default(now()),

                    Forms\Components\TextInput::make('peso')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('kg'),
                ]),

            Forms\Components\Section::make('Detalles de la consulta')
                ->schema([
                    Forms\Components\Textarea::make('motivo_consulta')
                        ->required()
                        ->rows(3),

                    Forms\Components\Textarea::make('examen_fisico')
                        ->rows(3),

                    Forms\Components\Textarea::make('diagnostico')
                        ->required()
                        ->rows(3),

                    Forms\Components\Textarea::make('tratamiento')
                        ->required()
                        ->rows(3),

                    Forms\Components\Textarea::make('observaciones')
                        ->rows(3),
                ]),

            Forms\Components\Section::make('Vacunas aplicadas')
                ->schema([
                    Forms\Components\Repeater::make('vacunas')
                        ->relationship()
                        ->schema([
                            Forms\Components\Hidden::make('clinic_id')
                                ->default(fn() => auth()->user()->clinic_id ?? auth()->user()->clinic->id),
                            Forms\Components\Hidden::make('user_id')
                                ->default(fn() => auth()->id()),
                            Forms\Components\Hidden::make('mascota_id')
                                ->default(fn(Forms\Get $get) => $get('../../mascota_id')),
                            Forms\Components\TextInput::make('nombre_vacuna')
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_aplicacion')
                                ->default(now())
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_revacunacion'),
                            Forms\Components\TextInput::make('lote'),
                            Forms\Components\Textarea::make('observaciones')
                                ->rows(2),
                        ])
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->addActionLabel('Agregar vacuna'),
                ])
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mascota.nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_consulta')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('motivo_consulta')
                    ->limit(50),
                Tables\Columns\TextColumn::make('diagnostico')
                    ->limit(50),
                Tables\Columns\TextColumn::make('vacunas_count')
                    ->counts('vacunas')
                    ->label('Vacunas'),
            ])
            ->defaultSort('fecha_consulta', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistorialMedicos::route('/'),
            'create' => Pages\CreateHistorialMedico::route('/create'),
            'edit' => Pages\EditHistorialMedico::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
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
