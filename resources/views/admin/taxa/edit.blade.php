@extends('layouts.dashboard', ['title' => __('navigation.edit_taxon')])

@section('content')
    <div class="box">
        <nz-taxon-form
            action="{{ route('api.taxa.update', $taxon) }}"
            method="PUT"
            :ranks="{{ $ranks }}"
            :conservation-legislations="{{ $conservationLegislations }}"
            :conservation-documents="{{ $conservationDocuments }}"
            :red-lists="{{ $redLists }}"
            :red-list-categories="{{ $redListCategories }}"
            :stages="{{ $stages }}"
            :taxon="{{ $taxon }}"
            :native-names="{{ $taxon->getAttributeTranslations('native_name') }}"
            :descriptions="{{ $taxon->getAttributeTranslations('description') }}"
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
