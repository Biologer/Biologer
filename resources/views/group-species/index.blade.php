@extends('layouts.main')

@section('content')
    <div class="container pb-8">
        <nav class="pagination is-species mb-8 mt-4" role="navigation" aria-label="pagination">
            <h1 class="is-size-3 pagination-title">
                {{ $group->name }}
            </h1>

            <div class="pagination-search">
                <a href="{{ route('groups.index') }}" class="button">
                    @include('components.icon', ['icon' => 'th'])
                    <span>{{ __('navigation.groups') }}</span>
                </a>
            </div>
        </nav>

        <div class="columns is-multiline">
            @foreach($species->chunk(10) as $chunk)
                <div class="column is-one-third has-text-centered">
                    @foreach($chunk as $sp)
                        <div class="block">
                            <a href="{{ route('groups.species.show', [
                                'group' => $group->id,
                                'species' => $sp->id,
                            ]) }}">{{ $sp->name }}</a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{ $species->links() }}
    </div>
@endsection
