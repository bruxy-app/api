<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'response',
        'response_at',
        'notification_uuid',
        'treatment_uuid',
    ];

    protected $casts = [
        'response_at' => 'datetime',
    ];
}
