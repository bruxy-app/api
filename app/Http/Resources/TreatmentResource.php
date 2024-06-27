<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'starts_at' => $this->starts_at->format('d/m/Y H:i'),
            'ends_at' => $this->ends_at->format('d/m/Y H:i'),
            'minimum_percentage' => $this->minimum_percentage,
            'status' => $this->status,
            'actual_end' => $this->actual_end,
            'questions_per_day' => $this->questions_per_day,
            'duration' => $this->ends_at->diffInDays($this->starts_at),
            'patient' => $this->whenLoaded('patient', $this->patient),
            'responsible' => $this->whenLoaded('responsible', $this->responsible),
            'notifications' => $this->whenLoaded('notifications', fn () => NotificationResource::collection($this->notifications)),
        ];
    }
}
