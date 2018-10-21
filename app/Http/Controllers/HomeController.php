<?php

namespace App\Http\Controllers;

use App\Announcement;

class HomeController extends Controller
{
    /**
     * Show paginated list of announcements.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $announcements = Announcement::latest();

        if (auth()->guest()) {
            $announcements->wherePrivate(false);
        }

        return view('home', [
            'announcements' => $announcements->take(5)->get(),
        ]);
    }
}
