<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Notification extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'sent_at',
        'question',
        'options',
        'question_uuid',
        'treatment_uuid'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'options' => 'array'
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'treatment_uuid', 'uuid');
    }

    public function response(): HasOne
    {
        return $this->hasOne(NotificationResponse::class, 'notification_uuid', 'uuid');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_uuid', 'uuid');
    }

    protected static function newFactory(): Factory
    {
        return NotificationFactory::new();
    }
}
