<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\MascotaResource\Pages;
use App\Filament\Clinic\Resources\MascotaResource\RelationManagers;
use App\Models\Mascota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MascotaResource extends Resource
{
    protected static ?string $model = Mascota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->label('Cliente')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombre} {$record->apellido}"),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tipo')
                    ->options([
                        'Perro' => 'Perro',
                        'Gato' => 'Gato',
                        'Ave' => 'Ave',
                        'Otro' => 'Otro'
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('fecha_nacimiento'),
                Forms\Components\Select::make('sexo')
                    ->options([
                        'Masculino' => 'Macho',
                        'Femenino' => 'Hembra',
                        'Desconocido' => 'Desconocido'
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notas')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Dueño')
                    ->formatStateUsing(fn ($record) => $record->cliente->nombre . ' ' . $record->cliente->apellido)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('edad')
                    ->label('Edad'),
                Tables\Columns\TextColumn::make('sexo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();

        if ($user->clinic_id) {
            return parent::getEloquentQuery()
                ->where('clinic_id', $user->clinic_id);
        }

        $clinic = \App\Models\Clinic::where('admin_id', $user->id)->first();
        return parent::getEloquentQuery()
            ->where('clinic_id', $clinic?->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMascotas::route('/'),
            'create' => Pages\CreateMascota::route('/create'),
            'edit' => Pages\EditMascota::route('/{record}/edit'),
        ];
    }
}
