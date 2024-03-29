@extends('layouts.dashboard', ['title' => __('navigation.new_observation')])

@section('content')
    <div class="box">
        <nz-field-observation-form
            action="{{ route('api.field-observations.store') }}"
            method="POST"
            redirect-url="{{ route('contributor.field-observations.index') }}"
            cancel-url="{{ route('contributor.field-observations.index') }}"
            :data-licenses="{{ json_encode(\App\License::getOptions()) }}"
            :image-licenses="{{ json_encode(\App\ImageLicense::getOptions()) }}"
            :sexes="{{ \App\Sex::options() }}"
            :observation-types="{{ \App\ObservationType::all() }}"
            :atlas-codes="{{ \App\AtlasCode::all() }}"
            :default-stage="{{ json_encode($defaultStage) }}"
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
