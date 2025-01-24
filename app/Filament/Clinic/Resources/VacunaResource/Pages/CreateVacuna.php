<?php

namespace App\Filament\Clinic\Resources\VacunaResource\Pages;

use App\Filament\Clinic\Resources\VacunaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVacuna extends CreateRecord
{
    protected static string $resource = VacunaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['clinic_id'] = auth()->user()->clinic_id ?? auth()->user()->clinic->id;
        $data['user_id'] = auth()->id();
        return $data;
    }
}
