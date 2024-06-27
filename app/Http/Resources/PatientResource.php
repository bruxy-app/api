<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{

    public static $wrap = "";
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'user' => $this->whenLoaded('user', fn() => UserResource::make($this->user)),
            'treatment' => $this->whenLoaded('treatment', fn() => TreatmentResource::make($this->treatment)),
        ];
    }
}
