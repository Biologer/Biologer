@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        @include('partials.literature-observation-details', compact('literatureObservation'))

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <a
                        href="{{ route('admin.literature-observations.edit', $literatureObservation) }}"
                        class="button is-primary is-outlined"
                    >{{ __('buttons.edit') }}</a>
                </div>
            </div>
        </div>

        <hr>

        <h2 class="is-size-4">{{ __('Activity Log') }}</h2>

        <nz-literature-observation-activity-log :activities="{{ $literatureObservation->activity }}"/>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('curator.approved-observations.index') }}">{{ __('navigation.literature_observations') }}</a></li>
            <li class="is-active"><a>{{ $literatureObservation->id }}</a></li>
        </ul>
    </div>
@endsection
