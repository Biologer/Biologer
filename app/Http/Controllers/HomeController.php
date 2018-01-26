<?php

namespace App\Http\Controllers;

use App\ViewGroup;

class HomeController extends Controller
{
    /**
     * Display the main website page.
     *
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        return view('home', [
            'rootGroups' => ViewGroup::roots()->with('groups')->get(),
        ]);
    }
}
