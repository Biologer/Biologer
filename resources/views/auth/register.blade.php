@extends('layouts.master')

@section('content')
    <section class="hero is-fullheight bg-light is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-4 is-offset-4">
                        <h1 class="title">
                          Register
                        </h1>
                        <form action="{{ url('/register') }}" method="POST" class="box">
                            {{ csrf_field() }}

                            <b-field label="First Name"
                                message="{{ $errors->first('first_name') }}"
                                type="{{ $errors->has('first_name') ? 'is-danger' : '' }}">
                                <b-input type="text"
                                    placeholder="First Name"
                                    value="{{ old('first_name') }}"
                                    name="first_name">
                                </b-input>
                            </b-field>

                            <b-field label="Last Name"
                                message="{{ $errors->first('last_name') }}"
                                type="{{ $errors->has('last_name') ? 'is-danger' : '' }}">
                                <b-input type="text"
                                    placeholder="Last Name"
                                    value="{{ old('last_name') }}"
                                    name="last_name">
                                </b-input>
                            </b-field>

                            <b-field label="Email"
                                message="{{ $errors->first('email') }}"
                                type="{{ $errors->has('email') ? 'is-danger' : '' }}">
                                <b-input type="email"
                                    placeholder="Email"
                                    value="{{ old('email') }}"
                                    name="email">
                                </b-input>
                            </b-field>

                            <b-field label="Password"
                                message="{{ $errors->first('password') }}"
                                type="{{ $errors->has('password') ? 'is-danger' : '' }}">
                                <b-input type="password"
                                    placeholder="Password"
                                    name="password">
                                </b-input>
                            </b-field>

                            <b-field label="Repeat password"
                                message="{{ $errors->first('password_confirmation') }}"
                                type="{{ $errors->has('password_confirmation') ? 'is-danger' : '' }}">
                                <b-input type="password"
                                    placeholder="Password"
                                    name="password_confirmation">
                                </b-input>
                            </b-field>

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary">Register</button>
                                </div>
                            </div>
                        </form>
                        <p class="has-text-centered">
                            Already registered? <a href="{{ route('login') }}" class="has-text-primary">Click here to login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
