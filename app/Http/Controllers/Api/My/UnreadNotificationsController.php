<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class UnreadNotificationsController
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Parameters from query
        $since    = $request->query('updated_after');
        $page     = (int) $request->query('page', 1);
        $perPage  = (int) $request->query('per_page', 50);

        // Base query
        $query = $user->unreadNotifications();

        // If timestamp is provided, convert and filter
        if (!empty($since) && is_numeric($since)) {
            $query->where('updated_at', '>', date('Y-m-d H:i:s', (int) $since));
        }

        // Paginate manually to honor page & per_page parameters
        $notifications = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return NotificationResource::collection($notifications);
    }
}
