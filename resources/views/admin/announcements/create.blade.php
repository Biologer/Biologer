@extends('layouts.dashboard', ['title' => __('navigation.new_announcement')])

@section('content')
    <div class="box">
        <nz-announcement-form
            action="{{ route('api.announcements.store') }}"
            method="POST"
            redirect-url="{{ route('admin.announcements.index') }}"
            cancel-url="{{ route('admin.announcements.index') }}"
        ></nz-announcement-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.announcements.index') }}">{{ __('navigation.announcements') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new_announcement') }}</a></li>
        </ul>
    </div>
@endsection
