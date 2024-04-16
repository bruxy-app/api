<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'response',
        'response_at',
        'notification_uuid',
    ];

    protected $casts = [
        'response_at' => 'datetime',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_uuid', 'uuid');
    }
}
