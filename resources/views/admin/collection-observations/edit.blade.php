@extends('layouts.dashboard', ['title' => __('navigation.edit_observation')])

@section('content')
    <div class="box">
        <nz-collection-observation-form
            action="{{ route('api.collection-observations.update', $collectionObservation) }}"
            method="PUT"
            redirect-url="{{ route('admin.collection-observations.index') }}"
            cancel-url="{{ route('admin.collection-observations.index') }}"
            :observation="{{ json_encode($collectionObservation->toFlatArray()) }}"
            :image-licenses="{{ json_encode(\App\License::getOptions()) }}"
            :sexes="{{ \App\Sex::options() }}"
            :dispositions="{{ \App\CollectionSpecimenDisposition::options() }}"
            :validity-options="{{ \App\ObservationIdentificationValidity::options() }}"
            should-confirm-submit
            confirm-submit-message="{{ __('Reason for changing data. Please try to be precise in order to keep the track of changes and ensure data verification.') }}"
            should-ask-reason
            should-confirm-cancel
            submit-only-dirty
        ></nz-collection-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.collection-observations.index') }}">{{ __('navigation.collection_observations') }}</a></li>
            <li><a href="{{ route('admin.collection-observations.show', $collectionObservation) }}">{{ $collectionObservation->id }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
