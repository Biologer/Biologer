@extends('layouts.dashboard', ['title' => __('navigation.new_taxon')])

@section('content')
    <div class="box">
        <nz-taxon-form
            action="{{ route('api.taxa.store') }}"
            method="POST"
            redirect-url="{{ route('admin.taxa.index') }}"
            cancel-url="{{ route('admin.taxa.index') }}"
            :ranks="{{ $ranks }}"
            :conservation-legislations="{{ $conservationLegislations }}"
            :conservation-documents="{{ $conservationDocuments }}"
            :red-lists="{{ $redLists }}"
            :red-list-categories="{{ $redListCategories }}"
            :stages="{{ $stages }}"
            :taxonomy="{{ $taxonomy }}"
            should-confirm-cancel
        ></nz-taxon-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.taxa.index') }}">{{ __('navigation.taxa') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.new') }}</a></li>
        </ul>
    </div>
@endsection
