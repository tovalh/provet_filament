<?php
// app/Models/HistorialMedico.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HistorialMedico extends Model
{
    use SoftDeletes;

    protected $table = 'historiales_medicos';

    protected $fillable = [
        'clinic_id',
        'mascota_id',
        'user_id',
        'fecha_consulta',
        'peso',
        'motivo_consulta',
        'examen_fisico',
        'diagnostico',
        'tratamiento',
        'observaciones',
    ];

    protected $casts = [
        'fecha_consulta' => 'date',
        'peso' => 'decimal:2'
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

    public function vacunas(): HasMany
    {
        return $this->hasMany(Vacuna::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
