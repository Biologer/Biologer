@extends('layouts.master')

@section('body')
    <nz-navbar inline-template>
        <nav class="navbar is-primary">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item" href="{{ url('/') }}">
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
                        <a class="navbar-item" @click="toggleSidebar">
                            @include('components.icon', ['icon' => 'bell'])
                        </a>

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
                                    <span>&nbsp;Contributor Area</span>
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
                    </div>
                </div>
            </div>

            <nz-sidebar :active="showSidebar" @close="showSidebar = false"></nz-sidebar>
        </nav>
    </nz-navbar>

    <nav class="navbar has-shadow">
        <div class="container">
            <div class="navbar-menu" :class="{ 'is-active': active }">
                <div class="navbar-start">
                    <div class="navbar-item">
                        @yield('breadcrumbs')
                    </div>
                </div>
                <div class="navbar-end">
                    <div class="navbar-item">
                        @yield('createButton')
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

@endsection
