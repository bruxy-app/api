<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid;

    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
        'verified_at',
        'clinic_uuid'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_uuid', 'uuid');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'responsible_uuid', 'uuid');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_uuid', 'uuid');
    }

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
