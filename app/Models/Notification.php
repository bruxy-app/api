<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Notification extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'sent_at',
        'questions',
        'response',
        'response_at',
        'treatment_uuid'
    ];

    protected $casts = [
        'questions' => 'array',
        'sent_at' => 'datetime',
        'options' => 'array',
        'response_at' => 'datetime'
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'treatment_uuid', 'uuid');
    }

    protected static function newFactory(): Factory
    {
        return NotificationFactory::new();
    }
}
