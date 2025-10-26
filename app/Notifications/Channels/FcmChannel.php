<?php

namespace App\Notifications\Channels;

use App\Services\FirebaseV1;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    public function send(FirebaseV1 $notifiable, Notification $notification)
    {
        // Expect notifications to implement a toFcm() method
        if (! method_exists($notification, 'toFcm')) {
            return;
        }

        $message = $notification->toFcm($notifiable);

        // Prefer per-user topic over device tokens
        $userId = $notifiable->id ?? null;
        if (! $userId) {
            return;
        }

        FirebaseV1::sendToUser(
            $userId,
            $message['type'] ?? 'generic',
            $message['data'] ?? []
        );
    }
}
