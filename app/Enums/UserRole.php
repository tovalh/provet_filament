<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case STAFF = 'staff';
    case ASSISTANT = 'assistant';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::DOCTOR => 'Veterinario',
            self::STAFF => 'Personal',
            self::ASSISTANT => 'Asistente'
        };
    }
}
