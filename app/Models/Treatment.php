<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Treatment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'starts_at',
        'ends_at',
        'minimum_percentage',
        'status',
        'actual_end',
        'patient_uuid',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'actual_end' => 'datetime',
    ];
}
