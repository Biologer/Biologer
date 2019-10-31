<?php

namespace App\Http\Controllers;

use App\Announcement;

class AnnouncementsController
{
    /**
     * List paginated announcements.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Announcement::with('creator')->latest()->thatAreTranslated();

        if (auth()->guest()) {
            $query->wherePrivate(false);
        }

        return view('announcements.index', [
            'announcements' => $query->paginate(),
        ]);
    }

    /**
     * View the announcement.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\View\View
     */
    public function show(Announcement $announcement)
    {
        abort_if($this->shouldHide($announcement), 404);

        return view('announcements.show', [
            'announcement' => $announcement->markAsRead(),
        ]);
    }

    private function shouldHide($announcement)
    {
        return ! $announcement->isTranslated() || ($announcement->private && auth()->guest());
    }
}
