<?php

namespace App\Filament\Clinic\Resources\ClienteResource\Pages;

use App\Filament\Clinic\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCliente extends CreateRecord
{
    protected static string $resource = ClienteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $clinic = \App\Models\Clinic::where('admin_id', auth()->id())->first();

        if (!$clinic) {
            throw new \Exception('No se encontró una clínica asociada a este usuario.');
        }

        $data['clinic_id'] = $clinic->id;
        return $data;
    }
    // Agregar esto para redirigir al listado después de crear
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
