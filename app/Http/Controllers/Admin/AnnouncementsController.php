<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;
use App\AnnouncementType;
use App\Http\Controllers\Controller;

class AnnouncementsController extends Controller
{
    public function index()
    {
        return view('admin.announcements.index');
    }

    public function create()
    {
        return view('admin.announcements.create', [
            'types' => collect(AnnouncementType::toArray())->values()
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'types' => collect(AnnouncementType::toArray())->values()

        ]);
    }
}
