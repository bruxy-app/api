<?php

namespace App\Models;

use Database\Factories\ClinicFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Clinic extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'clinic_uuid', 'uuid');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'clinic_uuid', 'uuid');
    }

    public function patients(): HasMany
    {
        return $this->users()->where('type', 'patient');
    }

    protected static function newFactory(): Factory
    {
        return ClinicFactory::new();
    }
}
