<?php

namespace App\Notifications\Channels;

use App\PendingNotification;
use Illuminate\Notifications\Notification;

class UnreadSummaryMailChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        return PendingNotification::create([
            'notification_id' => $notification->id,
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id' => $notifiable->id,
            'notification' => serialize($notification),
        ]);
    }
}
