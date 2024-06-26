<?php

namespace App\Models;

use Database\Factories\TreatmentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Treatment extends Model
{
    use HasFactory, HasUuid;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'starts_at',
        'ends_at',
        'minimum_percentage',
        'status',
        'questions_per_day',
        'actual_end',
        'clinic_uuid',
        'patient_uuid',
        'responsible_uuid',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'actual_end' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_uuid', 'uuid');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_uuid', 'uuid');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'treatment_uuid', 'uuid');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_uuid', 'uuid');
    }

    protected static function newFactory(): Factory
    {
        return TreatmentFactory::new();
    }
}
