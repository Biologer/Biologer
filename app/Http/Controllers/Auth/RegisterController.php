<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\License;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,filter', 'max:255', 'unique:users'],
            'institution' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha_verification_code' => ['required', 'captcha'],
            'data_license' => ['required', Rule::in(License::activeIds())],
            'image_license' => ['required', Rule::in(License::activeIds())],
            'accept' => ['required', 'accepted'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'institution' => $data['institution'],
            'password' => Hash::make($data['password']),
            'settings' => [
                'data_license' => (int) $data['data_license'],
                'image_license' => (int) $data['image_license'],
                'language' => app()->getLocale(),
            ],
        ]);
    }
}
