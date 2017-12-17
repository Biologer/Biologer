@extends('layouts.main')

@section('content')
    <section class="section is-hidden-touch mb-8">
        <div class="container has-text-centered">
            <a href="{{ url('/') }}"><img src="{{ asset('img/logo.svg') }}" class="image banner-image mx-auto"></a>
        </div>
    </section>

    <div class="container pb-8">
        <h2 class="is-size-2">Resend verification link</h2>

        <form action="{{ route('auth.verify.resend') }}" method="post">
            {{ csrf_field() }}

            <input type="hidden" name="email" value="{{ $user->email }}">

            <div class="field">
                <div class="field">
                    <nz-captcha url="{{ Captcha::url() }}"></nz-captcha>
                </div>

                <div class="control">
                    <input name="captcha_code"
                        class="input{{ $errors->has('captcha_code') ? ' is-danger' : '' }}"
                        placeholder="CAPTCHA Code">
                </div>

                <p class="help{{ $errors->has('captcha_code') ? ' is-danger' : '' }}">{{ $errors->first('captcha_code') }}</p>
            </div>

            <button type="submit" class="button">Re-send verification link</button>
        </form>
    </div>

@endsection
