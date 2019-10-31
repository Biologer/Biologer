<?php

namespace App\Http\Controllers\Preferences;

use App\Events\UserProfileUpdated;
use Illuminate\Http\Request;

class GeneralPreferencesController
{
    /**
     * Display user's profile preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('preferences.general', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user's profile preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->user()->update($request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string'],
        ], [], [
            'first_name' => __('labels.register.first_name'),
            'last_name' => __('labels.register.last_name'),
            'institution' => __('labels.register.institution'),
        ]));

        UserProfileUpdated::dispatch($request->user());

        return back()->withSuccess(__('Profile updated successfully.'));
    }
}
