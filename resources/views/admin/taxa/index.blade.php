@extends('layouts.dashboard', ['title' => __('navigation.taxa')])

@section('content')
    <div class="box">
        <nz-taxa-table
            list-route="api.taxa.index"
            edit-route="admin.taxa.edit"
            delete-route="api.taxa.destroy"
            :ranks="{{ json_encode(\App\Taxon::getRankOptions()) }}">
        </nz-taxa-table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.taxa') }}</a></li>
        </ul>
    </div>
@endsection

@section('createButton')
    <a href="{{ route('admin.taxa.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new') }}</span>
    </a>
@endsection
