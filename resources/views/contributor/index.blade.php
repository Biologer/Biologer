@extends('layouts.dashboard')

@section('content')
    <div class="container is-fluid py-4">
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
                </aside>
            </div>

            <div class="column">
                <div class="columns">
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <h3 class="is-uppercase is-size-6">My Field Observations</h3>

                            <div class="is-size-1">
                                {{ $observationCount }}
                            </div>

                            <a href="{{ route('contributor.field-observations.index') }}">See all</a>
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
        &nbsp;
        New Observation
    </a>
@endsection
