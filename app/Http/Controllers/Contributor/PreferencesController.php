<?php

namespace App\Http\Controllers\Contributor;

use App\Settings;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PreferencesController extends Controller
{
    /**
     * Display user's preferences.
     *
     * @return \Iluuminate\View\View
     */
    public function index()
    {
        return view('contributor.preferences.index', [
            'preferences' => auth()->user()->settings(),
        ]);
    }

    public function update()
    {
        $data = request()->validate([
            'data_license' => [Rule::in(Settings::availableDataLicenses())],
            'image_license' => [Rule::in(Settings::availableImageLicenses())],
        ]);

        auth()->user()->settings()->merge($data);

        return back();
    }
}
