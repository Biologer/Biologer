@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        @include('partials.collection-observation-details', compact('collectionObservation'))

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <a
                        href="{{ route('admin.collection-observations.edit', $collectionObservation) }}"
                        class="button is-primary is-outlined"
                    >{{ __('buttons.edit') }}</a>
                </div>
            </div>
        </div>

        <hr>

        <h2 class="is-size-4">{{ __('Activity Log') }}</h2>

        <nz-collection-observation-activity-log :activities="{{ $collectionObservation->activity }}"/>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.collection-observations.index') }}">{{ __('navigation.collection_observations') }}</a></li>
            <li class="is-active"><a>{{ $collectionObservation->id }}</a></li>
        </ul>
    </div>
@endsection
