<?php

namespace App\Http\ViewComposers;

use App\Announcement;
use Illuminate\View\View;

class DashboardComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('lastAnnouncement', Announcement::latest()->thatAreTranslated()->first());

        $view->with('hasUnreadNotifications', auth()->user()->unreadNotifications()->exists());
    }
}
