<?php

namespace App\Notifications\Channels;

use App\Services\FirebaseV1;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        // let the notification build a small payload for FCM
        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $token = $notifiable->routeNotificationFor('fcm');
        if (!$token) {
            return; // user has no device token
        }

        $message = $notification->toFcm($notifiable); // ['title' => ..., 'body' => ..., 'data' => [...]]
        FirebaseV1::sendToToken(
            $token,
            $message['title'] ?? 'Notification',
            $message['body']  ?? '',
            $message['data']  ?? []
        );
    }
}
