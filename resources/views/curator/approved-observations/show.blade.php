@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        @include('partials.field-observation-details', compact('fieldObservation'))

        @if ($fieldObservation->isApproved() && auth()->user()->can('update', $fieldObservation))
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <a
                            href="{{ route('curator.approved-observations.edit', $fieldObservation) }}"
                            class="button is-primary is-outlined"
                        >{{ __('buttons.edit') }}</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('curator.approved-observations.index') }}">{{ __('navigation.approved_observations') }}</a></li>
            <li class="is-active"><a>{{ $fieldObservation->id }}</a></li>
        </ul>
    </div>
@endsection
