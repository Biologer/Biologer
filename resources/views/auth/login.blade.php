@extends('layouts.master')

@section('body')
    <section class="hero min-screen-height bg-light is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-4 is-offset-4">
                        <h1 class="title">
                          Login
                        </h1>
                        <form action="{{ url('/login') }}" method="POST" class="box">
                            {{ csrf_field() }}

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

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary">Login</button>
                                </div>
                            </div>
                        </form>
                        <p class="has-text-centered">
                            Don't have an account? <a href="{{ route('register') }}">Click here to register</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
