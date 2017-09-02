@extends('layouts.master')

@section('content')
    <nz-navbar inline-template>
        <nav class="navbar has-shadow">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item is-hidden-desktop" href="{{ url('/') }}">
                        <b class="is-size-4">{{ config('app.name') }}</b>
                    </a>

                    <div class="navbar-burger" @click="toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="navbar-menu" :class="{ 'is-active': active }">
                    <div class="navbar-end">
                        @auth
                            <div class="navbar-item">
                                <a href="{{ route('field-observations.create') }}" class="button is-primary">
                                    @include('components.icon', ['icon' => 'plus'])
                                    <span>New Observation</span>
                                </a>
                            </div>

                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link is-hidden-touch">
                                    @include('components.icon', ['icon' => 'user'])
                                </a>

                                <div class="navbar-dropdown is-boxed is-right">
                                    <div class="navbar-item is-hidden-touch">
                                        <b class="is-size-6">{{ auth()->user()->full_name }}</b>
                                    </div>
                                    <hr class="navbar-divider">
                                    <a class="navbar-item" href="{{ route('contributor.index') }}">
                                        @include('components.icon', ['icon' => 'dashboard'])
                                        &nbsp;Contributor Area
                                    </a>
                                    <hr class="navbar-divider">
                                    <a href="{{ route('logout') }}"
                                        class="navbar-item"
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="navbar-item">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <a href="{{ route('login') }}" class="button is-primary">
                                            Login
                                        </a>
                                    </div>

                                    <div class="control">
                                        <a href="{{ route('register') }}" class="button is-default">
                                            Register
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </nz-navbar>

    <section class="hero">
        <div class="hero-body has-text-centered">
            <div class="container">
                <h1 class="title">
                    {{ config('app.name') }}
                </h1>
            </div>
        </div>
    </section>
@endsection
