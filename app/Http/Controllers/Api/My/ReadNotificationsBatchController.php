<?php

namespace App\Http\Controllers\Api\My;

use Illuminate\Http\Request;

class ReadNotificationsBatchController
{
    /**
     * Mark notifications with given IDs as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(Request $request)
    {
        $query = $request->user()->unreadNotifications();

        if (! $request->input('all')) {
            $query->whereIn('id', $request->input('notifications_ids', []));
        }

        $query->update(['read_at' => now()]);

        return [
            'meta' => [
                'has_unread' => $request->user()->unreadNotifications()->exists(),
            ],
        ];
    }
}
