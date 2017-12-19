@extends('layouts.main')

@section('content')
    <section class="hero min-h-screen bg-light is-bold">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Resend verification link</h1>

                <div class="box border-t-4 border-primary">
                    <form action="{{ route('auth.verify.resend') }}" method="POST">
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

                        <div class="field">
                            <button type="submit" class="button is-primary">Re-send verification link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
