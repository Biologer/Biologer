@extends('layouts.dashboard', ['title' => 'New Observation'])

@section('content')
    <div class="box">
        <field-observation-form
            action="{{ route('api.field-observations.store') }}"
            method="POST"
            redirect="{{ route('contributor.field-observations.index') }}"
            photo-upload-url="{{ route('api.uploads.store') }}"
            photo-remove-url="{{ route('api.uploads.destroy') }}"
            :licenses="{{ json_encode(\App\License::getAvailable()) }}"
            :sexes="{{ json_encode(\App\Observation::SEX_OPTIONS) }}"
        ></fild-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('contributor.field-observations.index') }}">My Field Observations</a></li>
            <li class="is-active"><a>New</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
