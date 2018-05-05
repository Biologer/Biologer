@extends('layouts.main', ['title' => __('navigation.view_groups')])

@section('content')
    <section class="section min-h-screen">
        @if($rootGroups->count() > 1)
            <b-tabs type="is-boxed" position="is-centered" class="block is-in-container" v-cloak>
                @foreach($rootGroups as $rootGroup)
                    <b-tab-item label="{{ $rootGroup->name }}">
                        @include('groups.partial', ['groups' => $rootGroup->groups])
                    </b-tab-item>
                @endforeach
            </b-tabs>
        @elseif($rootGroups->count() > 0)
            @include('groups.partial', ['groups' => $rootGroups->first()->groups])
        @else
            Currently in progress...
        @endif
    </section>
@endsection
