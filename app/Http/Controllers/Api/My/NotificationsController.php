<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class UnreadNotificationsController extends Controller
{
    public function index(Request $request)
    {
        return NotificationResource::collection($request->user()->notifications()->paginate());
    }
}
