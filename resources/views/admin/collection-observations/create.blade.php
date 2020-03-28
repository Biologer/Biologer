@extends('layouts.dashboard', ['title' => __('navigation.new_collection_observation')])

@section('content')
    <div class="box">
        <nz-collection-observation-form
            action="{{ route('api.collection-observations.store') }}"
            method="POST"
            redirect-url="{{ route('admin.collection-observations.index') }}"
            cancel-url="{{ route('admin.collection-observations.index') }}"
            :sexes="{{ \App\Sex::options() }}"
            :validity-options="{{ \App\ObservationIdentificationValidity::options() }}"
            should-confirm-cancel
            submit-more
            submit-more-with-same-taxon
        ></nz-collection-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.collection-observations.index') }}">{{ __('navigation.collection_observations') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new') }}</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
