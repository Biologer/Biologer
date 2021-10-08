@extends('layouts.master', ['title' => __('navigation.login')])

@section('body')
<nav class="navbar shadow border-t-4 border-primary">
    <div class="container is-fluid">
        <div class="navbar-brand justify-between">
            <a class="navbar-item" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="{{ config('app.name') }}" class="navbar-logo">
            </a>

            <div class="navbar-item is-hidden-desktop">
                <a href="{{ route('register') }}" class="button is-outlined is-secondary">
                    {{ __('navigation.register') }}
                </a>
            </div>
        </div>

        <div class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item">
                    <a href="{{ route('register') }}" class="button is-outlined is-secondary">
                        {{ __('navigation.register') }}
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
                <div class="column is-half-tablet is-one-third-desktop is-offset-one-quarter-tablet is-offset-one-third-desktop">
                    <h1 class="title">
                      {{ __('navigation.login') }}
                    </h1>

                    <div class="box border-t-4 border-primary">
                        @if (session('verified'))
                            <article class="message is-success">
                                <div class="message-body">
                                    {{ __('auth.verified') }}
                                </div>
                            </article>
                        @endif

                        @if (session()->has('success'))
                            <article class="message is-success">
                                <div class="message-body">
                                    {{ session('success') }}
                                </div>
                            </article>
                        @elseif (session()->has('info'))
                            <article class="message is-info">
                                <div class="message-body">
                                    {{ session('info') }}
                                </div>
                            </article>
                        @endif

                        @if ($errors->isNotEmpty())
                            <article class="message is-danger">
                                <div class="message-body">
                                    <ul>
                                        @foreach ($errors->all() as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </article>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="field">
                                <label class="label">{{ __('labels.login.email') }}</label>
                                <div class="control">
                                    <input type="email"
                                        name="email"
                                        class="input"
                                        placeholder="{{ __('labels.login.email') }}"
                                        value="{{ old('email') }}"
                                        autofocus>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">{{ __('labels.login.password') }}</label>
                                <div class="control">
                                    <input type="password"
                                        name="password"
                                        class="input"
                                        placeholder="{{ __('labels.login.password') }}">
                                </div>
                            </div>

                            <div class="field">
                                <label class="checkbox is-not-custom">
                                    <input type="checkbox"
                                        name="remember"
                                        {{ old('remember') ? ' checked' : '' }}>
                                    {{ __('labels.login.remember_me') }}
                                </label>
                            </div>

                            <div class="level">
                                <div class="level-left">
                                    <button type="submit" class="button is-primary">{{ __('buttons.login') }}</button>
                                </div>

                                <div class="level-right">
                                    <a href="{{ route('password.request') }}">{{ __('labels.login.forgot_password') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')

@endsection
