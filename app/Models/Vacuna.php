<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacuna extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'mascota_id',
        'user_id',
        'historial_medico_id',
        'nombre_vacuna',
        'fecha_aplicacion',
        'fecha_revacunacion',
        'lote',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'fecha_revacunacion' => 'date'
    ];

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function historialMedico(): BelongsTo
    {
        return $this->belongsTo(HistorialMedico::class);
    }
}
