@extends('layouts.dashboard', ['title' => __('navigation.edit_publication')])

@section('content')
    <div class="box">
        <nz-publication-form
            action="{{ route('api.publications.update', $publication) }}"
            method="PUT"
            redirect-url="{{ route('admin.publications.index') }}"
            cancel-url="{{ route('admin.publications.index') }}"
            :publication="{{ $publication }}"
            :publication-types="{{ $publicationTypes }}"
        ></nz-publication-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.publications.index') }}">{{ __('navigation.publications') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection
