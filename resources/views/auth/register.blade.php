@extends('layouts.master')

@section('body')
<nav class="navbar has-shadow border-t-4 border-primary">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="{{ config('app.name') }}" class="navbar-logo">
            </a>
        </div>
    </div>
</nav>

<section class="hero min-h-screen bg-light is-bold">
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <h1 class="title">
                      Register
                    </h1>

                    <div class="box border-t-4 border-primary">
                        <form action="{{ url('/register') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <label class="label">First Name</label>

                                        <div class="control">
                                            <input type="ext"
                                                name="first_name"
                                                class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}"
                                                placeholder="First Name"
                                                value="{{ old('first_name') }}"
                                                autofocus>
                                        </div>

                                        <p class="help{{ $errors->has('first_name') ? ' is-danger' : '' }}">{{ $errors->first('first_name') }}</p>
                                    </div>
                                </div>

                                <div class="column">
                                    <div class="field">
                                        <label class="label">Last Name</label>

                                        <div class="control">
                                            <input type="ext"
                                                name="last_name"
                                                class="input{{ $errors->has('last_name') ? ' is-danger' : '' }}"
                                                placeholder="Last Name"
                                                value="{{ old('last_name') }}">
                                        </div>

                                        <p class="help{{ $errors->has('last_name') ? ' is-danger' : '' }}">{{ $errors->first('last_name') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Email</label>

                                <div class="control">
                                    <input type="email"
                                        name="email"
                                        class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                        placeholder="Email"
                                        value="{{ old('email') }}">
                                </div>

                                <p class="help{{ $errors->has('email') ? ' is-danger' : '' }}">{{ $errors->first('email') }}</p>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <label class="label">Password</label>

                                        <div class="control">
                                            <input type="password"
                                                name="password"
                                                class="input{{ $errors->has('password') ? ' is-danger' : '' }}"
                                                placeholder="Password">
                                        </div>

                                        <p class="help{{ $errors->has('password') ? ' is-danger' : '' }}">{{ $errors->first('password') }}</p>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="field">
                                        <label class="label">Repeat Password</label>

                                        <div class="control">
                                            <input type="password"
                                                name="password_confirmation"
                                                class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                                                placeholder="Password">
                                        </div>

                                        <p class="help{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}">{{ $errors->first('password_confirmation') }}</p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <label class="label">Data License</label>
                                        <p>Choose how you would like to share data with the others.</p>
                                        <div class="control">
                                            <b-tooltip
                                                label="You agree to share all of the data about species occurrences (except
                                                for the endangered species that could be listed out by taxonomic
                                                experts)."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="data_license" value="10" checked>
                                                    Open Data (<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike</a> licence)
                                                </label>
                                            </b-tooltip>
                                            <b-tooltip
                                                label="As above, but excludes commercial use of the data without your
                                                agreement. Your data will still be freely used for conservation or
                                                scientific purposes."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="data_license" value="20">
                                                    Open Data (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike</a> licence)
                                                </label>
                                            </b-tooltip>
                                            <b-tooltip
                                                label="Similar to Open Data, except the data is rescaled to 10x10 km2
                                                resolution. Full resolution data is available to you, administrators of
                                                the web site and the taxonomic experts."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="data_license" value="30">
                                                    Partially open data (in resolution of 10x10 km2)
                                                </label>
                                            </b-tooltip>
                                            <b-tooltip
                                                label="We highly discourage you to use this option. Only you, administrators
                                                of the web site and the taxonomic experts are allowed to view and use
                                                the data."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="data_license" value="40">
                                                    ‎Closed Data (not shown on the maps)
                                                </label>
                                            </b-tooltip>
                                        </div>
                                    </div>
                                </div>

                                <div class="column">
                                    <div class="field">
                                        <label class="label">Image License</label>
                                        <p>Chose how you would like to share images with the others.</p>
                                        <div class="control">
                                            <b-tooltip
                                                label="You agree to share all of the images uploaded to the database. The
                                                images could be used and redistributed by anyone as long as the
                                                author’s contribution is recognized"
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="image_license" value="10" checked>
                                                    Share images (<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike</a> licence)
                                                </label>
                                            </b-tooltip>
                                            <b-tooltip
                                                label="As above, but excludes commercial use of the data without your
                                                agreement."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="image_license" value="20">
                                                    Share images (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike</a> licence)
                                                </label>
                                            </b-tooltip>
                                            <b-tooltip
                                                label="You agree to share images within the database web site, but restrict
                                                any other usages. Images will get clear watermark with copyright
                                                information and the web site logo."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="image_license" value="30">
                                                    Share images on the site (keep the authorship of the images)
                                                </label>
                                            </b-tooltip>
                                            <b-tooltip
                                                label="This action is highly discouraged. Only administrators and the
                                                taxonomic experts will be able to view images and species records could
                                                not be easily verified and reviewed in the public domain."
                                                multilined>
                                                <label class="radio">
                                                    <input type="radio" name="image_license" value="40">
                                                    ‎Restrict images (images are not shown in the public domain)
                                                </label>
                                            </b-tooltip>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="field">
                                <label class="label" for="captcha_verification_code">Verification Code</label>

                                <div class="field">
                                    <nz-captcha url="{{ Captcha::url() }}"></nz-captcha>
                                </div>

                                <div class="control">
                                    <input name="captcha_verification_code"
                                        id="captcha_verification_code"
                                        class="input{{ $errors->has('captcha_verification_code') ? ' is-danger' : '' }}"
                                        placeholder="Verification code">
                                </div>
                                <p class="help{{ $errors->has('captcha_verification_code') ? ' is-danger' : '' }}">{{ $errors->first('captcha_verification_code') }}</p>
                            </div>

                            <hr>

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <p class="has-text-centered">
                        Already registered? <a href="{{ route('login') }}">Click here to login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
