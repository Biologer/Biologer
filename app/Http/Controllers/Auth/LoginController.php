<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/contributor';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'email' => trans('labels.login.email'),
            'password' => trans('labels.login.password'),
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $redirectUrl = redirect()->intended($this->redirectPath())->getTargetUrl();

        if ($this->isNotLocalizedRoute($redirectUrl)) {
            return redirect($redirectUrl);
        }

        return redirect(LaravelLocalization::getLocalizedURL(
            $user->preferredLocale(),
            $redirectUrl
        ));
    }

    /**
     * Check if route is under localization middleware.
     *
     * @param  string  $url
     * @return bool
     */
    protected function isNotLocalizedRoute($url)
    {
        try {
            $route = Route::getRoutes()->match(Request::create($url));

            return ! in_array('localizationRedirect', $route->gatherMiddleware());
        } catch (\Exception $e) {
            return false;
        }
    }
}
