<?php

namespace App\Models;

use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Patient extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'access_code',
        'user_uuid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function treatment()
    {
        return $this->hasOne(Treatment::class, 'patient_uuid', 'uuid');
    }

    protected static function newFactory(): Factory
    {
        return PatientFactory::new();
    }
}
