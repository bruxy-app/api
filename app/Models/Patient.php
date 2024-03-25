<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Patient extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'sex',
        'access_code',
        'user_uuid'
    ];
}
