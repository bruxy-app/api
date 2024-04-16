<?php

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_uuid', 'uuid');
    }

    protected static function newFactory(): Factory
    {
        return QuestionFactory::new();
    }
}
