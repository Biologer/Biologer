@extends('layouts.dashboard', ['title' => __('navigation.new_publication')])

@section('content')
    <div class="box">
        <nz-publication-form
            action="{{ route('api.publications.store') }}"
            method="POST"
            redirect-url="{{ url()->previous(route('admin.publications.index')) }}"
            cancel-url="{{ url()->previous(route('admin.publications.index')) }}"
            :publication-types="{{ $publicationTypes }}"
        ></nz-publication-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.publications.index') }}">{{ __('navigation.publications') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new_publication') }}</a></li>
        </ul>
    </div>
@endsection
