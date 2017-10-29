@extends('layouts.master')

@section('body')
    <section class="hero min-screen-height bg-light is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-4 is-offset-4">
                        <h1 class="title">
                          Register
                        </h1>
                        <form action="{{ url('/register') }}" method="POST" class="box">
                            {{ csrf_field() }}

                            <div class="field">
                                <label class="label">First Name</label>
                                <div class="control">
                                    <input type="ext"
                                        name="first_name"
                                        class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}"
                                        placeholder="First Name"
                                        value="{{ old('first_name') }}">
                                </div>
                                <p class="help{{ $errors->has('first_name') ? ' is-danger' : '' }}">{{ $errors->first('first_name') }}</p>
                            </div>

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
                                <label class="label">Repeat Password</label>
                                <div class="control">
                                    <input type="password"
                                        name="password_confirmation"
                                        class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                                        placeholder="Password">
                                </div>
                                <p class="help{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}">{{ $errors->first('password_confirmation') }}</p>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary">Register</button>
                                </div>
                            </div>
                        </form>
                        <p class="has-text-centered">
                            Already registered? <a href="{{ route('login') }}">Click here to login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
