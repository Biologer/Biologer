<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationsPreferencesController
{
    /**
     * Display user's notifications preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('preferences.notifications', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user's notifications preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'field_observation_approved.*' => ['nullable', 'boolean'],
            'field_observation_edited.*' => ['nullable', 'boolean'],
            'field_observation_moved_to_pending.*' => ['nullable', 'boolean'],
            'field_observation_marked_unidentifiable.*' => ['nullable', 'boolean'],
            'field_observation_for_approval.*' => ['nullable', 'boolean'],
        ]);

        $this->updateNotificationsPreferences($request);

        return back()->withSuccess(__('Notifications preferences are saved.'));
    }

    /**
     * Update notification preferences using data from request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function updateNotificationsPreferences($request)
    {
        $notifications = $request->user()->settings()->get('notifications');

        foreach ($this->getPreferenceData($request) as $key => $value) {
            Arr::set($notifications, $key, $value);
        }

        $request->user()->settings()->set('notifications', $notifications);
    }

    /**
     * Get data we need to update notifications preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getPreferenceData($request)
    {
        $data = [];

        foreach ($this->getPreferenceAttributes($request) as $attribute) {
            $data[$attribute] = (bool) $request->input($attribute, false);
        }

        return $data;
    }

    /**
     * Get list of attributes we need to extract from request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getPreferenceAttributes($request)
    {
        $attributes = [
            'field_observation_approved.database',
            'field_observation_edited.database',
            'field_observation_moved_to_pending.database',
            'field_observation_marked_unidentifiable.database',
            'field_observation_approved.mail',
            'field_observation_edited.mail',
            'field_observation_moved_to_pending.mail',
            'field_observation_marked_unidentifiable.mail',
        ];

        if ($request->user()->hasRole('curator')) {
            $attributes[] = 'field_observation_for_approval.database';
            $attributes[] = 'field_observation_for_approval.mail';
        }

        return $attributes;
    }
}
