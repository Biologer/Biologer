<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    /**
     * Display verify page.
     *
     * @param  string  $email
     * @return \Illuminate\View\View
     */
    public function show($email)
    {
        $user = User::findByEmail($email);

        abort_if($user->verified, 404);

        return view('auth.verify', ['user' => $user]);
    }

    /**
     * Verify the user email using provided token.
     *
     * @param  \App\VerificationToken  $verificationToken
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(VerificationToken $verificationToken)
    {
        abort_if($verificationToken->userVerified(), 404);

        $verificationToken->markUserAsVerified();

        return redirect()->route('login')
            ->with('success', 'Your account is now verified.');
    }

    /**
     * Resend verification token to the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend()
    {
        request()->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(function ($query) {
                    return $query->where('verified', false);
                }),
            ],
            'captcha_code' => 'required|captcha',
        ]);

        User::findByEmail(request('email'))->sendVerificationEmail();

        return redirect()->route('login')
            ->with('info', 'Verification link has been sent to your email. Please check you inbox.');
    }
}
