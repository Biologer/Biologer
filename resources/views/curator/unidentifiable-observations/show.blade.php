@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        @include('partials.field-observation-details', compact('fieldObservation'))

        @if ($fieldObservation->unidentifiable && auth()->user()->can('update', $fieldObservation))
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <a
                            href="{{ route('curator.unidentifiable-observations.edit', $fieldObservation) }}"
                            class="button is-primary is-outlined"
                        >{{ __('buttons.edit') }}</a>
                    </div>
                </div>
            </div>
        @endif

        <hr>

        <h2 class="is-size-4">{{ __('Activity Log') }}</h2>

        <nz-field-observation-activity-log :activities="{{ $fieldObservation->activity }}"/>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('curator.approved-observations.index') }}">{{ __('navigation.unidentifiable_observations') }}</a></li>
            <li class="is-active"><a>{{ $fieldObservation->id }}</a></li>
        </ul>
    </div>
@endsection
