@extends('layouts.main', ['title' => __('navigation.view_groups')])

@section('content')
    <section class="section">
        @if($rootGroups->count() > 1)
            <b-tabs type="is-boxed" position="is-centered" class="block is-in-container" v-cloak>
                @foreach($rootGroups as $rootGroup)
                    <b-tab-item>
                        <div slot="header" class="flex is-flex-center">
                            @if($rootGroup->image_url)
                                <img src="{{ $rootGroup->image_url }}" alt="{{ $rootGroup->name }}" class="h-5 w-5 mr-2">
                            @endif

                            {{ $rootGroup->name }}
                        </div>

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
