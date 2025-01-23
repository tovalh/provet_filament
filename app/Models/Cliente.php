<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    //
    use HasFactory,softDeletes;

    protected $table = 'clientes';
    protected $fillable = [
        'clinic_id',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'direccion',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class);
    }

    // En tu modelo (por ejemplo, Cliente.php)

    protected function nombre(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst(strtolower($value)),
        );
    }
    protected function apellido(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst(strtolower($value)),
        );
    }

}
