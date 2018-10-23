<?php

namespace App\Http\Controllers\Api\My;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;

class UnreadNotificationsController extends Controller
{
    public function index(Request $request)
    {
        return NotificationResource::collection($request->user()->notifications()->paginate());
    }
}
