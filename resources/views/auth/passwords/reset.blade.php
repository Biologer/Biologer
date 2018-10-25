@extends('layouts.main', ['title' => __('navigation.reset_password')])

@section('content')
<section class="hero min-h-screen bg-light is-bold">
    <div class="hero-body">
        <div class="container mt-8">
            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <h1 class="title">{{ __('navigation.reset_password') }}</h1>

                    <div class="box border-t-4 border-primary">
                        <form method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="field is-required">
                                <label for="email" class="label">{{ __('labels.reset_password.email') }}</label>

                                <div class="control">
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                        placeholder="{{ __('labels.reset_password.email') }}"
                                        value="{{ $email ?? old('email') }}"
                                        required
                                        autofocus>
                                </div>

                                @if ($errors->has('email'))
                                    <p class="help is-danger">{{ $errors->first('email') }}</p>
                                @endif
                            </div>

                            <div class="field is-required">
                                <label for="password" class="label">{{ __('labels.reset_password.password') }}</label>

                                <div class="control">
                                    <input id="password"
                                        type="password"
                                        class="input{{ $errors->has('password') ? ' is-danger' : '' }}"
                                        name="password"
                                        required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="field is-required">
                                <label for="password-confirm" class="label">{{ __('labels.reset_password.password_confirmation') }}</label>
                                <div class="control">
                                    <input id="password-confirm"
                                        type="password"
                                        class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                                        name="password_confirmation"
                                        required>

                                    @if ($errors->has('password_confirmation'))
                                        <p class="help is-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                            </div>


                            <div class="field">
                                <button type="submit" class="button is-primary">
                                    {{ __('buttons.reset_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
