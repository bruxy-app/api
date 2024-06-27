<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RawNotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'questions' => $this->questions,
            'response' => $this->response,
            'response_at' => $this->response_at,
            'sent_at' => $this->sent_at,
        ];
    }
}
