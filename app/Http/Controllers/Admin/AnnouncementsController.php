<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;
use App\Http\Controllers\Controller;

class AnnouncementsController extends Controller
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
