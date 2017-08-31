@extends('layouts.master')

@section('content')
    <section class="hero is-fullheight bg-light is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-4 is-offset-4">
                        <h1 class="title">
                          Login
                        </h1>
                        <form action="{{ url('/login') }}" method="POST" class="box">
                            {{ csrf_field() }}

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

                            <div class="field">
                                <b-checkbox name="remember">Remember me</b-checkbox>
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
