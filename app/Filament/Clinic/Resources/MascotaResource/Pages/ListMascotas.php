<?php

namespace App\Filament\Clinic\Resources\MascotaResource\Pages;

use App\Filament\Clinic\Resources\MascotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMascotas extends ListRecords
{
    protected static string $resource = MascotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
