<?php

namespace App\Http\Controllers\Preferences;

use App\ImageLicense;
use App\License;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LicensePreferencesController
{
    /**
     * Display user's license preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('preferences.license', [
            'dataLicense' => $request->user()->settings()->data_license,
            'imageLicense' => $request->user()->settings()->image_license,
        ]);
    }

    /**
     * Update user's license preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->user()->settings()->merge($request->validate([
            'data_license' => ['required', Rule::in(License::ids())],
            'image_license' => ['required', Rule::in(ImageLicense::ids())],
        ]));

        return back()->withSuccess(__('Successfully updated.'));
    }
}
