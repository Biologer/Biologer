@extends('layouts.dashboard', ['title' => 'Edit Observation'])

@section('content')
    <div class="box">
        <field-observation-form
            action="{{ route('api.field-observations.update', $observation) }}"
            method="PUT"
            redirect="{{ route('curator.pending-observations.index') }}"
            photo-upload-url="{{ route('api.photo-uploads.store') }}"
            photo-remove-url="{{ route('api.photo-uploads.destroy') }}"
            :licenses="{{ json_encode(\App\License::getAvailable()) }}"
            :sexes="{{ json_encode(\App\Observation::SEX_OPTIONS) }}"
            :observation="{{ $observation }}"
        ></fild-observation-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('curator.pending-observations.index') }}">Pending Observations</a></li>
            <li class="is-active"><a>Edit</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
