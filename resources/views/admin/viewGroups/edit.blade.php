@extends('layouts.dashboard', ['title' => __('navigation.edit_view_group')])

@section('content')
    <div class="box">
        <nz-view-group-form
            action="{{ route('api.view-groups.update', $group) }}"
            method="PUT"
            redirect-url="{{ route('admin.view-groups.index') }}"
            cancel-url="{{ route('admin.view-groups.index') }}"
            :group="{{ $group->makeHidden('translations') }}"
            :root-groups="{{ $rootGroups }}"
            :names="{{ $group->getAttributeTranslations('name') }}"
            :descriptions="{{ $group->getAttributeTranslations('description') }}"
            should-confirm-cancel
            submit-only-dirty
        ></nz-view-group-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.view-groups.index') }}">{{ __('navigation.view_groups') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection
