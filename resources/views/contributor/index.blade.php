@extends('layouts.contributor')

@section('content')
    <div class="container is-fluid pt-4">
        <div class="columns">
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">
                        General
                    </p>

                    <ul class="menu-list">
                        <li><a href="{{ route('contributor.field-observations.index') }}">My Observations</a></li>
                    </ul>

                    <p class="menu-label">
                        Curating
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
                            <h3 class="is-uppercase is-size-6"> My Observations</h3>

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
