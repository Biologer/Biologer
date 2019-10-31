<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\FieldObservation;
use App\User;

class HomeController
{
    /**
     * Show paginated list of announcements.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->thatAreTranslated();

        if (auth()->guest()) {
            $announcements->wherePrivate(false);
        }

        return view('home', [
            'announcements' => $announcements->take(5)->get(),
            'community' => config('biologer.community.name'),
            'userCount' => User::count(),
            'observationCount' => FieldObservation::count(),
        ]);
    }
}
