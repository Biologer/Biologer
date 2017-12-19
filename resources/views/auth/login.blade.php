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
                <div class="column is-4 is-offset-4">
                    <h1 class="title">
                      Login
                    </h1>

                    <div class="box border-t-4 border-primary">
                        <form action="{{ url('/login') }}" method="POST">
                            {{ csrf_field() }}

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

                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control">
                                    <input type="email"
                                        name="email"
                                        class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                        placeholder="Email"
                                        value="{{ old('email') }}"
                                        autofocus>
                                </div>
                                <p class="help{{ $errors->has('email') ? ' is-danger' : '' }}">{{ $errors->first('email') }}</p>
                            </div>

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

                            <div class="field">
                                <label class="checkbox is-not-custom">
                                    <input type="checkbox"
                                        name="remember"
                                        {{ old('remember') ? ' checked' : '' }}>
                                    Remember me
                                </label>
                            </div>

                            <div class="level">
                                <div class="level-left">
                                    <button type="submit" class="button is-primary">Login</button>
                                </div>

                                <div class="level-right">
                                    <a href="{{ route('password.request') }}">Forgot password?</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <p class="has-text-centered">
                        Don't have an account? <a href="{{ route('register') }}">Click here to register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
