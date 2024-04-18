<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Treatment;
use Carbon\Carbon;

class CreateNotificationsForTreatmentService
{
    public function handle(Treatment $treatment)
    {
        $duration = $treatment->starts_at->diffInDays($treatment->ends_at);
        for ($i = 0; $i < $duration; $i++) {
            for ($j = 0; $j < $treatment->questions_per_day; $j++) {
                // amount of time between each question, considering it has to start at 8 and end at 18
                $sentAt = (new Carbon())->setTime(8, 0, 0)->addDays($i)->addHours($j * 2);
                Notification::create([
                    'treatment_uuid' => $treatment->uuid,
                    'questions' => $treatment->clinic->questions->map(function ($question) {
                        return [
                            'uuid' => $question->uuid,
                            'question' => $question->question,
                            'options' => $question->options,
                        ];
                    }),
                    // between 8:00 and 18:00
                    'sent_at' => $sentAt
                ]);
            }
        }
    }
}
