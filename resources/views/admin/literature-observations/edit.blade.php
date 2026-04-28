@extends('layouts.dashboard', ['title' => __('navigation.edit_observation')])

@section('content')
    <div class="box">
        <nz-literature-observation-form
            action="{{ route('api.literature-observations.update', $literatureObservation) }}"
            method="PUT"
            redirect-url="{{ route('admin.literature-observations.index') }}"
            cancel-url="{{ route('admin.literature-observations.index') }}"
            :observation="{{ json_encode($literatureObservation->toFlatArray()) }}"
            :sexes="{{ \App\Sex::options() }}"
            :validity-options="{{ \App\ObservationIdentificationValidity::options() }}"
            should-confirm-submit
            confirm-submit-message="{{ __('Reason for changing data. Please try to be precise in order to keep the track of changes and ensure data verification.') }}"
            should-ask-reason
            should-confirm-cancel
            submit-only-dirty
        ></nz-literature-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.literature-observations.index') }}">{{ __('navigation.literature_observations') }}</a></li>
            <li><a href="{{ route('admin.literature-observations.show', $literatureObservation) }}">{{ $literatureObservation->id }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
