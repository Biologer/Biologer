<?php

namespace App\Http\Controllers;

use App\ViewGroup;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'rootGroups' => ViewGroup::roots()->with('groups')->get(),
        ]);
    }
}
