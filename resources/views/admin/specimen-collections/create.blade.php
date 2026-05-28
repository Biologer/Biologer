@extends('layouts.dashboard', ['title' => __('navigation.new_specimen_collection')])

@section('content')
    <div class="box">
        <nz-specimen-collection-form
            action="{{ route('api.specimen-collections.store') }}"
            method="POST"
            redirect-url="{{ url()->previous(route('admin.specimen-collections.index')) }}"
            cancel-url="{{ url()->previous(route('admin.specimen-collections.index')) }}"
        ></nz-specimen-collection-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.specimen-collections.index') }}">{{ __('navigation.specimen_collections') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new_specimen_collection') }}</a></li>
        </ul>
    </div>
@endsection
