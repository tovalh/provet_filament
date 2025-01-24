<?php

namespace App\Filament\Clinic\Resources\VacunaResource\Pages;

use App\Filament\Clinic\Resources\VacunaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVacuna extends EditRecord
{
    protected static string $resource = VacunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
