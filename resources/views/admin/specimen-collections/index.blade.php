@extends('layouts.dashboard', ['title' => __('navigation.specimen_collections')])

@section('content')
    <div class="box">
        <nz-specimen-collections-table
            list-route="api.specimen-collections.index"
            edit-route="admin.specimen-collections.edit"
            delete-route="api.specimen-collections.destroy"
            empty="{{ __('No data...') }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.specimen_collections') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('admin.specimen-collections.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_specimen_collection') }}</span>
    </a>
@endsection
