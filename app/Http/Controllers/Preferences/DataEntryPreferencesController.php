<?php

namespace App\Http\Controllers\Preferences;

use App\Events\UserProfileUpdated;
use Illuminate\Http\Request;

class DataEntryPreferencesController
{
    /**
     * Update user's profile preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'default_stage_adult' => ['nullable', 'boolean'],
        ]);

        $request->user()->settings()->merge([
            'default_stage_adult' => $request->boolean('default_stage_adult'),
        ]);

        UserProfileUpdated::dispatch($request->user());

        return back()->withSuccess(__('Profile updated successfully.'));
    }
}
