<?php

namespace App\Http\Controllers\Contributor;

use App\License;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PreferencesController extends Controller
{
    /**
     * Display user's preferences.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.preferences.index', [
            'preferences' => auth()->user()->settings(),
        ]);
    }

    /**
     * Update user's preferences.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        auth()->user()->settings()->merge(request()->validate([
            'data_license' => [Rule::in(License::ids()->all())],
            'image_license' => [Rule::in(License::ids()->all())],
        ]));

        return back();
    }
}
