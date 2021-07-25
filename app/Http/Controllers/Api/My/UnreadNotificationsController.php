<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class UnreadNotificationsController
{
    public function index(Request $request)
    {
        return NotificationResource::collection(
            $request->user()->unreadNotifications()->paginate()
        );
    }
}
