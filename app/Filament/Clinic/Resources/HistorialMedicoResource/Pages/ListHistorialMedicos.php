<?php

namespace App\Filament\Clinic\Resources\HistorialMedicoResource\Pages;

use App\Filament\Clinic\Resources\HistorialMedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHistorialMedicos extends ListRecords
{
    protected static string $resource = HistorialMedicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
