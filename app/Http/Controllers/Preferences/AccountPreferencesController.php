<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AccountPreferencesController extends Controller
{
    /**
     * Display user's account preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('preferences.account');
    }

    /**
     * Change users password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'password' => __('labels.register.password'),
        ]);

        $request->user()->update(['password' => Hash::make($request->input('password'))]);

        return back()->withSuccess(__('Password changed successfully.'));
    }
}
