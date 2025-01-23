<?php

namespace App\Filament\Clinic\Resources\MascotaResource\Pages;

use App\Filament\Clinic\Resources\MascotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMascota extends CreateRecord
{
    protected static string $resource = MascotaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $clinic = \App\Models\Clinic::where('admin_id', auth()->id())->first();

        if (!$clinic) {
            throw new \Exception('No se encontró una clínica asociada a este usuario.');
        }

        $data['clinic_id'] = $clinic->id;
        return $data;
    }

}
