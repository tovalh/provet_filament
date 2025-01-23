<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mascota extends Model
{
    //
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'clinic_id',
        'cliente_id',
        'nombre',
        'tipo',
        'fecha_nacimiento',
        'sexo',
        'notas',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',  // Esto nos permitirá trabajar con la fecha como objeto Carbon
    ];
    public function cliente(): BelongsTo{
        return $this->belongsTo(Cliente::class);
    }

    public function clinica():BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return 'Edad desconocida';
        }

        $años = $this->fecha_nacimiento->age;
        $meses = $this->fecha_nacimiento->diffInMonths(now()) % 12;

        if ($años > 0) {
            return $años . ' años ' . ($meses > 0 ? 'y ' . $meses . ' meses' : '');
        }

        return $meses . ' meses';
    }

    public function getSexoTextoAttribute()
    {
        return [
            'Masculino' => 'Macho',
            'Femenino' => 'Hembra',
            'Desconocido' => 'Desconocido'
        ][$this->sexo] ?? 'Desconocido';
    }

    // En tu modelo (por ejemplo, Cliente.php)

    protected function nombre(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst(strtolower($value)),
        );
    }

}
