<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Question extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'question',
        'options',
        'clinic_uuid',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
