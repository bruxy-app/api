<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Treatment;

class SyncTreatmentNotificationsService
{
    public function handle(Treatment $treatment)
    {
        $firstNotification = $treatment->notifications->first()->sent_at;
        $firstNotification->setTimeFrom([now()])->setTime(8, 0, 0);
        /** @var Notification $notification */
        foreach ($treatment->notifications as $index => $notification) {
            if ($index === 0) {
                $notification->update([
                    'sent_at' => $firstNotification
                ]);
                continue;
            }

            $notification->update([
                'sent_at' => $firstNotification->addHours(2)
            ]);
        }
    }
}
