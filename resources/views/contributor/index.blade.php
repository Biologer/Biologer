@extends('layouts.dashboard')

@section('content')
    <div class="container is-fluid p-4">
        <div class="columns">
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">
                        General
                    </p>

                    <ul class="menu-list">
                        <li><a href="{{ route('contributor.field-observations.index') }}">My Field Observations</a></li>
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
                <div class="level box">
                    <div class="level-item has-text-centered">
                        <div>
                            <p class="heading">My Field Observations</p>
                            <p class="title">{{ $observationCount }}</p>
                        </div>
                    </div>
                    <div class="level-item has-text-centered">
                        <div>
                            <p class="heading">Approved</p>
                            <p class="title">0</p>
                        </div>
                    </div>
                    <div class="level-item has-text-centered">
                        <div>
                            <p class="heading">Unsolved</p>
                            <p class="title">0</p>
                        </div>
                    </div>
                    <div class="level-item has-text-centered">
                        <div>
                            <p class="heading">Pending</p>
                            <p class="title">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li class="is-active"><a>Dashboard</a></li>
        </ul>
    </div>
@endsection

@section('createButton')
    <a class="button is-secondary is-outlined" href="{{ route('contributor.field-observations.create') }}">
        @include('components.icon', ['icon' => 'plus'])
        <span>New Observation<span>
    </a>
@endsection
