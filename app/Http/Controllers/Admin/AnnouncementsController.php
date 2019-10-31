<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;

class AnnouncementsController
{
    /**
     * Show page with list of announcements.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.announcements.index');
    }

    /**
     * Show form to create announcement.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Show announcement edit page.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\View\View
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
        ]);
    }
}
