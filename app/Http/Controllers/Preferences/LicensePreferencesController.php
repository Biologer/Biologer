<?php

namespace App\Http\Controllers\Preferences;

use App\License;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class LicensePreferencesController extends Controller
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
            'data_license' => ['required', Rule::in(License::activeIds())],
            'image_license' => ['required', Rule::in(License::activeIds())],
        ]));

        return back()->withSuccess(__('Successfully updated.'));
    }
}
