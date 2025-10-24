<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UnreadNotificationsController
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $page    = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 50);

        $query = $user->unreadNotifications();

        if ($since = $request->query('updated_after')) {
            $sinceAt = Carbon::createFromTimestampUTC((int) $since);

            \Log::debug('Filtering notifications created after', [
                'timestamp' => $since,
                'datetime_utc' => $sinceAt->copy()->setTimezone('UTC')->toDateTimeString(),
            ]);

            $query->where('created_at', '>', $sinceAt);
        }

        $notifications = $query
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return NotificationResource::collection($notifications);
    }
}
