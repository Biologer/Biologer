<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;

class AnnouncementsController
{
    public function index()
    {
        return view('admin.announcements.index');
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
        ]);
    }
}
