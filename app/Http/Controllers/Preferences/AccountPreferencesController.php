<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountPreferencesController
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

    /**
     * Change users password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'max:191', 'email:rfc,dns', 'confirmed', 'unique:users,email'],
        ], [], [
            'email' => __('labels.register.email'),
        ]);

        $request->user()->update(['email' => $request->input('email')]);

        $request->user()->email_verified_at = null;
        $request->user()->save();

        $request->user()->sendEmailVerificationNotification();

        return back()->withSuccess(__('Email changed successfully. Please verify your email.'));
    }

    /**
     * Delete user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->user()->deleteAccount($request->delete_observations);

        Auth::guard()->logout();

        $request->session()->invalidate();

        $message = $request->delete_observations
            ? 'You account has been deleted. Your observations will be deleted shortly.'
            : 'You account has been deleted.';

        return redirect()->route('home')->with('status', __($message));
    }
}
