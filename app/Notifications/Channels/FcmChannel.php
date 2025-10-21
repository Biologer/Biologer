<?php

namespace App\Notifications\Channels;

use App\Services\FirebaseV1;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toFcm')) {
            Log::info('FcmChannel: Notification has no toFcm() method.');
            return;
        }

        $token = $notifiable->routeNotificationFor('fcm');
        if (! $token) {
            Log::info('FcmChannel: No FCM token for user '.$notifiable->id);
            return;
        }

        $message = $notification->toFcm($notifiable);

        Log::info('FcmChannel sending to token', [
            'token' => $token,
            'payload' => $message,
        ]);

        FirebaseV1::sendToToken(
            $token,
            $message['title'] ?? 'Notification',
            $message['body'] ?? '',
            $message['data'] ?? []
        );
    }
}
