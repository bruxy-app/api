<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Notification extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'sent_at',
        'treatment_uuid'
    ];
    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
