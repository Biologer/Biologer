@extends('layouts.dashboard', ['title' => __('navigation.edit_announcement')])

@section('content')
    <div class="box">
        <nz-announcement-form
            action="{{ route('api.announcements.update', $announcement) }}"
            method="PUT"
            redirect-url="{{ route('admin.announcements.index') }}"
            cancel-url="{{ route('admin.announcements.index') }}"
            :announcement="{{ $announcement }}"
            :title="{{ $announcement->getAttributeTranslations('title') }}"
            :message="{{ $announcement->getAttributeTranslations('message') }}"
        ></nz-announcement-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.announcements.index') }}">{{ __('navigation.announcements') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection
