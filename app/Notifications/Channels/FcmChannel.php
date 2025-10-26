<?php

namespace App\Notifications\Channels;

use App\Services\FirebaseV1;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function send(FirebaseV1 $notifiable, Notification $notification)
    {
        // Expect notifications to implement a toFcm() method
        if (! method_exists($notification, 'toFcm')) {
            Log::info('FcmChannel: Notification has no toFcm() method.');

            return;
        }

        $message = $notification->toFcm($notifiable);

        // Prefer per-user topic over device tokens
        $userId = $notifiable->id ?? null;
        if (! $userId) {
            Log::warning('FcmChannel: Cannot determine user ID for topic sending.');

            return;
        }

        Log::info('FcmChannel sending to user topic', [
            'user_id' => $userId,
            'payload' => $message,
        ]);

        FirebaseV1::sendToUser(
            $userId,
            $message['type'] ?? 'generic',
            $message['data'] ?? []
        );
    }
}
