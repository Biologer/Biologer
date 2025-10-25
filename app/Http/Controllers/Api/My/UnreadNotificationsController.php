<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use App\Filters\UpdatedAfter;

class UnreadNotificationsController
{
    public function index(Request $request, UpdatedAfter $updatedAfter)
    {
        $user    = $request->user();
        $page    = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 50);

        $query = $user->unreadNotifications();
        $query = $updatedAfter->apply($query, $request->query('updated_after'), 'created_at');

        $notifications = $query
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return NotificationResource::collection($notifications);
    }
}
