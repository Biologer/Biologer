<?php

namespace App\Http\Controllers\Api;

use App\Announcement;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;

class ReadAnnouncementsController extends Controller
{
    public function store()
    {
        request()->validate([
            'announcement_id' => ['required', Rule::exists('announcements', 'id')],
        ]);

        $announcement = Announcement::find(request('announcement_id'));

        return new AnnouncementResource($announcement->markAsRead());
    }
}
