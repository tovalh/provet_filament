<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription_ends_at',
        'is_trial',
        'trial_ends_at',
        'clinic_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'admin_id');
    }
    public function managedClinic()
    {
        return $this->hasOne(Clinic::class, 'admin_id');
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }
    public function hasActiveSubscription()
    {
        if ($this->is_trial && $this->trial_ends_at > now()) {
            return true;
        }

        return $this->subscription_ends_at > now();
    }

    public function hasClinic()
    {
        return $this->clinic()->exists();
    }

    public function getClinicAttribute()
    {
        return $this->clinic_id
            ? $this->clinic
            : $this->managedClinic;
    }
}
