@extends('layouts.dashboard', ['title' => __('navigation.new_literature_observation')])

@section('content')
    <div class="box">
        <nz-literature-observation-form
            action="{{ route('api.literature-observations.store') }}"
            method="POST"
            redirect-url="{{ route('admin.literature-observations.index') }}"
            cancel-url="{{ route('admin.literature-observations.index') }}"
            :sexes="{{ \App\Sex::options() }}"
            :validity-options="{{ \App\LiteratureObservationIdentificationValidity::options() }}"
            should-confirm-cancel
            submit-more
            submit-more-with-same-taxon
        ></nz-literature-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.literature-observations.index') }}">{{ __('navigation.literature_observations') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new') }}</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
