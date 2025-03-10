@extends('layouts.dashboard', ['title' => __('navigation.taxa')])

@section('content')
    <div class="box">
        <nz-taxa-table
            list-route="api.taxa.index"
            edit-route="admin.taxa.edit"
            delete-route="api.taxa.destroy"
            export-url="{{ route('api.taxon-exports.store') }}"
            admin-export-url="{{ route('api.admin-taxon-exports.store') }}"
            :export-columns="{{ $exportColumns }}"
            :admin-export-columns="{{ $adminExportColumns }}"
            :ranks="{{ $ranks }}"
            :taxonomy="{{ $taxonomy }}"
            :taxonomy-link="'{{ $taxonomyLink }}'"
            empty="{{ __('No data...') }}"
            show-activity-log>
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

@section('navigationActions')
    @if ($taxonomy != 'true')
    <a href="{{ route('admin.taxa.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.add') }}</span>
    </a>
    @endif
@endsection
