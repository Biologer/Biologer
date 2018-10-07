<?php

namespace App\Http\Controllers;

use App\Announcement;

class AnnouncementsController
{
    public function show(Announcement $announcement)
    {
        abort_if($this->shouldHide($announcement), 404);

        return view('announcements.show', [
            'announcement' => $announcement->markAsRead(),
        ]);
    }

    private function shouldHide($announcement)
    {
        return ! $announcement->isTranslated() ||
            ($announcement->private && auth()->guest());
    }
}
