@extends('layouts.dashboard', ['title' => __('navigation.edit_taxon')])

@section('content')
    <div class="box">
        <nz-taxon-form
            action="{{ route('api.taxa.update', $taxon) }}"
            method="PUT"
            redirect-url="{{ route('admin.taxa.index') }}"
            cancel-url="{{ route('admin.taxa.index') }}"
            :ranks="{{ $ranks }}"
            :conservation-legislations="{{ $conservationLegislations }}"
            :conservation-documents="{{ $conservationDocuments }}"
            :red-lists="{{ $redLists }}"
            :red-list-categories="{{ $redListCategories }}"
            :stages="{{ $stages }}"
            :taxon="{{ $taxon }}"
            :native-names="{{ $taxon->getAttributeTranslations('native_name') }}"
            :descriptions="{{ $taxon->getAttributeTranslations('description') }}"
            should-confirm-submit
            confirm-submit-message="{{ __('Reason for changing data. Please try to be precise in order to keep the track of changes and ensure data verification.') }}"
            should-ask-reason
            should-confirm-cancel
            submit-only-dirty
        ></nz-taxon-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.taxa.index') }}">{{ __('navigation.taxa') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection
