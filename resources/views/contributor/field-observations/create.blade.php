@extends('layouts.dashboard', ['title' => __('navigation.new_observation')])

@section('content')
    <div class="box">
        <nz-field-observation-form
            action="{{ route('api.field-observations.store') }}"
            method="POST"
            redirect-url="{{ route('contributor.field-observations.index') }}"
            cancel-url="{{ route('contributor.field-observations.index') }}"
            :licenses="{{ json_encode(\App\License::getOptions()) }}"
            :sexes="{{ json_encode(\App\Observation::SEX_OPTIONS) }}"
            :observation-types="{{ App\ObservationType::all() }}"
            submit-more
            should-confirm-cancel
            @role(['admin', 'curator'])
            show-observer-identifier
            @endrole
        ></nz-fild-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('contributor.field-observations.index') }}">{{ __('navigation.my_field_observations') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new') }}</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
