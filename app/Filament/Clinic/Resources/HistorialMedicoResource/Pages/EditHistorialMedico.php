<?php

namespace App\Filament\Clinic\Resources\HistorialMedicoResource\Pages;

use App\Filament\Clinic\Resources\HistorialMedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHistorialMedico extends EditRecord
{
    protected static string $resource = HistorialMedicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
