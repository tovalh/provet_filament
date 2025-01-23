<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateClinic extends CreateRecord
{
    protected static string $resource = ClinicResource::class;

    // Verificamos antes de mostrar el formulario
    public function beforeFill(): void
    {
        // Si el usuario ya tiene una clínica, lo redirigimos
        if (auth()->user()->hasClinic()) {
            Notification::make()
                ->danger()
                ->title('Límite alcanzado')
                ->body('Solo puedes tener una clínica registrada.')
                ->send();

            // Redirigimos al listado de clínicas
            $this->redirect(static::getResource()::getUrl('index'));
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si por alguna razón llegan aquí, verificamos nuevamente
        if (auth()->user()->hasClinic()) {
            Notification::make()
                ->danger()
                ->title('Límite alcanzado')
                ->body('Solo puedes tener una clínica registrada.')
                ->send();

            $this->redirect(static::getResource()::getUrl('index'));
        }

        $data['admin_id'] = auth()->id();
        $data['invitation_code'] = \App\Models\Clinic::generateInvitationCode();
        return $data;
    }
}
