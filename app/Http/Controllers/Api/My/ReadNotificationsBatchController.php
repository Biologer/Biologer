<?php

namespace App\Http\Controllers\Api\My;

use App\Services\FirebaseV1;
use Illuminate\Http\Request;

class ReadNotificationsBatchController
{
    /**
     * Mark notifications with given IDs as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $query = $user->unreadNotifications();

        $all = $request->boolean('all', false);
        $ids = (array) $request->input('notifications_ids', []);

        if (! $all && empty($ids)) {
            return response()->json(['message' => 'No notifications selected to be marked as read.'], 400);
        }

        if (! $all) {
            $query->whereIn('id', $ids);
        }

        // Mark notifications as read
        $count = $query->update(['read_at' => now()]);

        // Send FCM notification(s)
        if ($count > 0) {
            if ($all) {
                // If all were marked as read, broadcast a generic "all read" event
                FirebaseV1::sendToUser(
                    $user->id,
                    'notification_read',
                    ['notification_id' => 'all']
                );
            } else {
                // Otherwise, broadcast each individually
                foreach ($ids as $notificationId) {
                    FirebaseV1::sendToUser(
                        $user->id,
                        'notification_read',
                        ['notification_id' => (string) $notificationId]
                    );
                }
            }
        }

        return [
            'meta' => [
                'has_unread' => $user->unreadNotifications()->exists(),
            ],
        ];
    }
}
