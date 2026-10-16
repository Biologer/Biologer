@extends('layouts.dashboard', ['title' => __('navigation.edit_collection')])

@section('content')
    <div class="box">
        <nz-specimen-collection-form
            action="{{ route('api.specimen-collections.update', $specimenCollection) }}"
            method="PUT"
            redirect-url="{{ route('admin.specimen-collections.index') }}"
            cancel-url="{{ route('admin.specimen-collections.index') }}"
            :specimen-collection="{{ $specimenCollection }}"
        ></nz-specimen-collection-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.specimen-collections.index') }}">{{ __('navigation.specimen_collections') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection
