@extends('layouts.dashboard', ['title' => __('navigation.edit_observation')])

@section('content')
    <div class="box">
        <nz-field-observation-form
            action="{{ route('api.field-observations.update', $observation) }}"
            method="PUT"
            redirect-url="{{ route('curator.approved-observations.index') }}"
            cancel-url="{{ route('curator.approved-observations.index') }}"
            :licenses="{{ json_encode(\App\License::getOptions()) }}"
            :sexes="{{ json_encode(\App\Observation::SEX_OPTIONS) }}"
            :observation-types="{{ $observationTypes }}"
            :observation="{{ $observation }}"
            should-confirm-submit
            confirm-submit-message="{{ __('Reason for changing data. Please try to be precise in order to keep the track of changes and ensure data verification.') }}"
            should-ask-reason
            should-confirm-cancel
            submit-only-dirty
            @role(['admin', 'curator'])
            show-observer-identifier
            @endrole
        ></nz-field-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('curator.approved-observations.index') }}">{{ __('navigation.approved_observations') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
