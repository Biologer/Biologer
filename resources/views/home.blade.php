@extends('layouts.master')

@section('content')
    <nz-navbar inline-template>
        <nav class="navbar has-shadow hero-border-top">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item is-hidden-desktop" href="{{ url('/') }}">
                        <h4 class="is-size-4 has-text-bold">{{ config('app.name') }}</h4>
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
                                <a href="{{ route('contributor.field-observations.create') }}" class="button is-primary">
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
                                        <a href="{{ route('register') }}" class="button is-outlined is-secondary">
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

    <section class="section is-hidden-touch bg-light">
        <div class="container has-text-centered">
            <img src="{{ asset('img/banner.png') }}" class="image" usemap="bannermap">
            <map name="bannermap">
                <area shape="rect" coords="935,40,1140,115" href="http://www.habiprot.org.rs" alt="Habiprot">
            </map>
        </div>
    </section>

    <b-tabs type="is-boxed bg-light" position="is-centered" class="bg-white" :animated="false">
        <b-tab-item label="Reptiles">
            <section class="section">
                <div class="container">
                    <div class="columns is-centered">
                        <div class="column">
                            <div class="bg-light" style="height: 150px">
                            </div>
                        </div>
                        <div class="column">
                            <div class="bg-light" style="height: 150px">
                            </div>
                        </div>
                        <div class="column">
                            <div class="bg-light" style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </b-tab-item>
        <b-tab-item label="Amphibians">
            <section class="section">
                <div class="container"></div>
            </section>
        </b-tab-item>
        <b-tab-item label="Insects">
            <section class="section">
                <div class="container"></div>
            </section>
        </b-tab-item>
    </b-tabs>
@endsection
