@extends('layouts.dashboard', ['title' => __('navigation.new_view_group')])

@section('content')
    <div class="box">
        <nz-view-group-form
            action="{{ route('api.view-groups.store') }}"
            method="POST"
            redirect-url="{{ route('admin.view-groups.index') }}"
            cancel-url="{{ route('admin.view-groups.index') }}"
            :root-groups="{{ $rootGroups }}"
            should-confirm-cancel
        ></nz-view-group-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.view-groups.index') }}">{{ __('navigation.view_groups') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new') }}</a></li>
        </ul>
    </div>
@endsection
