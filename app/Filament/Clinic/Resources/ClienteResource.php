<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ClienteResource\Pages;
use App\Filament\Clinic\Resources\ClienteResource\RelationManagers;
use App\Filament\Clinic\Resources\ClienteResource\RelationManagers\MascotasRelationManager;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Textarea::make('direccion')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->formatStateUsing(fn ($record) => "{$record->nombre} {$record->apellido}")
                    ->label('Nombre Completo')
                    ->extraAttributes(['class' => 'w-auto']) // Ajusta el ancho automáticamente
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellido', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mascotas_count')
                    ->label('Mascotas')
                    ->counts('mascotas')
                    ->alignCenter()
                    ->sortable()
                    ->description(fn (Cliente $record): string => $record->mascotas->pluck('nombre')->join(', ')),
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
                Tables\Actions\Action::make('mascotas')
                    ->iconButton()
                    ->icon('heroicon-o-heart')
                    ->label('Ver Mascotas')
                    ->url(fn (Cliente $record): string => MascotaResource::getUrl('index', ['cliente_id' => $record->id]))
                    ->openUrlInNewTab(),
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
            MascotasRelationManager::class,
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
