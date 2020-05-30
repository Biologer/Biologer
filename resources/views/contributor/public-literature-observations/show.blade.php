@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        @include('partials.literature-observation-details', compact('literatureObservation'))
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('contributor.public-literature-observations.index') }}">{{ __('navigation.literature_observations') }}</a></li>
            <li class="is-active"><a>{{ $literatureObservation->id }}</a></li>
        </ul>
    </div>
@endsection
