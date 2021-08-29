@extends('layouts.master', ['title' => __('navigation.register')])

@section('body')
<nav class="navbar shadow border-t-4 border-primary">
    <div class="container is-fluid">
        <div class="navbar-brand justify-between">
            <a class="navbar-item" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="{{ config('app.name') }}" class="navbar-logo">
            </a>

            <div class="navbar-item is-hidden-desktop">
                <a href="{{ route('login') }}" class="button is-outlined is-secondary">
                    {{ __('navigation.login') }}
                </a>
            </div>
        </div>

        <div class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item">
                    <a href="{{ route('login') }}" class="button is-outlined is-secondary">
                        {{ __('navigation.login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<section class="flex-1 hero bg-light is-bold">
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <h1 class="title">
                        {{ __('navigation.register') }}
                    </h1>

                    <nz-registration-form
                        :init-password-error="{{ json_encode($errors->first('password')) }}"
                        inline-template
                    >
                        <div class="box border-t-4 border-primary" inline-component>
                            <form action="{{ route('register') }}" method="POST">
                                {{ csrf_field() }}

                                <div class="columns">
                                    <div class="column">
                                        <div class="field is-required">
                                            <label class="label">{{ __('labels.register.first_name') }}</label>

                                            <div class="control">
                                                <input type="ext"
                                                    name="first_name"
                                                    class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}"
                                                    placeholder="{{ __('labels.register.first_name') }}"
                                                    value="{{ old('first_name') }}"
                                                    autofocus>
                                            </div>

                                            <p class="help{{ $errors->has('first_name') ? ' is-danger' : '' }}">{{ $errors->first('first_name') }}</p>
                                        </div>
                                    </div>

                                    <div class="column">
                                        <div class="field is-required">
                                            <label class="label">{{ __('labels.register.last_name') }}</label>

                                            <div class="control">
                                                <input type="ext"
                                                    name="last_name"
                                                    class="input{{ $errors->has('last_name') ? ' is-danger' : '' }}"
                                                    placeholder="{{ __('labels.register.last_name') }}"
                                                    value="{{ old('last_name') }}">
                                            </div>

                                            <p class="help{{ $errors->has('last_name') ? ' is-danger' : '' }}">{{ $errors->first('last_name') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('labels.register.institution') }}</label>

                                    <div class="control">
                                        <input type="text"
                                            name="institution"
                                            class="input{{ $errors->has('institution') ? ' is-danger' : '' }}"
                                            placeholder="{{ __('labels.register.institution') }}"
                                            value="{{ old('institution') }}">
                                    </div>

                                    <p class="help{{ $errors->has('institution') ? ' is-danger' : '' }}">{{ $errors->first('institution') }}</p>
                                </div>

                                <div class="field is-required">
                                    <label class="label">{{ __('labels.register.email') }}</label>

                                    <div class="control">
                                        <input type="email"
                                            name="email"
                                            class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                            placeholder="{{ __('labels.register.email') }}"
                                            value="{{ old('email') }}">
                                    </div>

                                    <p class="help{{ $errors->has('email') ? ' is-danger' : '' }}">{{ $errors->first('email') }}</p>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <div class="field is-required">
                                            <label class="label">{{ __('labels.register.password') }}</label>

                                            <div class="control">
                                                <input
                                                    type="password"
                                                    name="password"
                                                    class="input"
                                                    :class="{'is-danger': passwordIsInvalid}"
                                                    placeholder="{{ __('labels.register.password') }}"
                                                    v-model="password"
                                                    v-on:input="checkIfFixedPassword"
                                                    v-on:blur="checkPassword"
                                                />
                                            </div>

                                            <p v-if="passwordIsInvalid" class="help is-danger" v-cloak>@{{ passwordError }}</p>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="field is-required">
                                            <label class="label">{{ __('labels.register.password_confirmation') }}</label>

                                            <div class="control">
                                                <input
                                                    type="password"
                                                    name="password_confirmation"
                                                    class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                                                    placeholder="{{ __('labels.register.password') }}">
                                            </div>

                                            <p class="help{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}">{{ $errors->first('password_confirmation') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                @include('partials.licenses', [
                                    'dataLicense' => old('data_license', \App\License::firstId()),
                                    'imageLicense' => old('image_license', \App\ImageLicense::firstId()),
                                ])

                                <hr>

                                <div class="field is-required">
                                    <label class="label" for="captcha_verification_code">{{ __('labels.register.verification_code') }}</label>

                                    <div class="field">
                                        <nz-captcha url="{{ Captcha::url() }}"></nz-captcha>
                                    </div>

                                    <div class="control">
                                        <input name="captcha_verification_code"
                                            id="captcha_verification_code"
                                            class="input{{ $errors->has('captcha_verification_code') ? ' is-danger' : '' }}"
                                            placeholder="{{ __('labels.register.verification_code') }}">
                                    </div>
                                    <p class="help{{ $errors->has('captcha_verification_code') ? ' is-danger' : '' }}">{{ $errors->first('captcha_verification_code') }}</p>
                                </div>

                                <div class="field is-required">
                                    <label class="checkbox is-not-custom">
                                        <input type="checkbox"
                                            name="accept"
                                            {{ old('accept') ? ' checked' : '' }}>
                                        {!! __('labels.register.accept', ['url' => route('pages.privacy-policy')]) !!}
                                    </label>

                                    <p class="help{{ $errors->has('accept') ? ' is-danger' : '' }}">{{ $errors->first('accept') }}</p>
                                </div>

                                <hr>

                                <div class="field">
                                    <div class="control">
                                        <button type="submit" class="button is-primary" :disabled="shouldBeDisabled">{{ __('buttons.register') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </nz-registration-form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')

@endsection
