<?php

namespace App\Filament\Clinic\Resources\HistorialMedicoResource\Pages;

use App\Filament\Clinic\Resources\HistorialMedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHistorialMedico extends CreateRecord
{
    protected static string $resource = HistorialMedicoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['clinic_id'] = auth()->user()->clinic_id ?? auth()->user()->clinic->id;
        $data['user_id'] = auth()->id();

        return $data;
    }
}
