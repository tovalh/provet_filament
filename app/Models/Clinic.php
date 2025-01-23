<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'name',
        'email',
        'phone',
        'invitation_code',
        'address',
        'is_active',
        'is_trial',
        'trial_ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_trial' => 'boolean',
        'trial_ends_at' => 'datetime'
    ];

    // Relación con el usuario administrador
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relación con clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function staff()
    {
        return $this->hasMany(User::class);
    }

// Relación con mascotas
    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    public function getDomainAttribute()
    {
        return $this->subdomain . '.' . config('app.domain');
    }

    public static function generateInvitationCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('invitation_code', $code)->exists());

        return $code;
    }

    public function isActive()
    {
        return $this->is_active && $this->admin->hasActiveSubscription();
    }

    public function isTrialActive(): bool
    {
        return $this->is_trial && $this->trial_ends_at > now();
    }

    // Scope para obtener solo clínicas activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereHas('admin', function($q) {
                $q->where(function($q) {
                    $q->where('is_trial', true)
                        ->where('trial_ends_at', '>', now())
                        ->orWhere('subscription_ends_at', '>', now());
                });
            });
    }
}
