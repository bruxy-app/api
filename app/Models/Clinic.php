<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Clinic extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
    ];
}
