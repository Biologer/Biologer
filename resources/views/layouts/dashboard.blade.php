@extends('layouts.master')

@section('body')
    <nz-navbar inline-template>
        <nav class="navbar is-primary">
            <div class="container is-fluid">
                <div class="navbar-brand">
                    <a class="navbar-item" href="{{ url('/') }}">
                        <span class="is-size-4">{{ config('app.name') }}</span>
                    </a>

                    <a class="navbar-item" href="{{ route('contributor.index') }}" title="Dashboard">
                        @include('components.icon', ['icon' => 'dashboard'])
                    </a>

                    <a class="navbar-item" @click="toggleSidebar" title="Notifications">
                        @include('components.icon', ['icon' => 'bell'])
                    </a>

                    <div class="navbar-burger" :class="{ 'is-active': active }" @click="toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <div class="navbar-menu" :class="{ 'is-active': active }">
                    <div class="navbar-end">
                        <a href="{{ route('logout') }}"
                            class="navbar-item"
                            title="{{ auth()->user()->full_name }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <span>Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>

            <nz-sidebar :active="showSidebar" @close="showSidebar = false"></nz-sidebar>
        </nav>
    </nz-navbar>

    <nav class="navbar has-shadow second-navbar">
        <div class="container is-fluid">
            <div class="navbar-menu">
                <div class="navbar-start">
                    <div class="navbar-item">
                        @yield('breadcrumbs')
                    </div>
                </div>
                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="field is-grouped">
                            <div class="control">
                                @yield('createButton')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container is-fluid p-4">
        <div class="columns">
            <div class="column is-2">
                <aside class="menu">
                    <p class="menu-label">
                        My
                    </p>

                    <ul class="menu-list">
                        <li><a href="{{ route('contributor.field-observations.index') }}">Field Observations</a></li>
                    </ul>

                    <p class="menu-label">
                        Admin
                    </p>

                    <ul class="menu-list">
                        <li><a href="{{ route('admin.taxa.index') }}">Taxa</a></li>
                    </ul>

                    <ul class="menu-list">
                        <li><a href="{{ route('admin.pending-observations.index') }}">Pending Observations</a></li>
                    </ul>
                </aside>
            </div>

            <div class="column">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
