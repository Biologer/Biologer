@extends('layouts.main', ['title' => $species->name])

@section('content')
    <div class="container pb-8">
        <nav class="pagination is-species mb-8 mt-4" role="navigation" aria-label="pagination">
            <h1 class="is-size-3 pagination-title">
                <i>{{ $species->name }}</i>&nbsp;
                @if($species->native_name)
                    <small class="is-size-6">{{ $species->native_name }}</small>
                @endif
            </h1>

            <div class="pagination-search">
                <a href="{{ route('groups.index') }}" class="button" title="{{ __('navigation.groups') }}">
                    @include('components.icon', ['icon' => 'th'])
                    <span class="is-hidden-tablet-only">{{ __('navigation.groups') }}</span>
                </a>

                <a href="{{ $species->indexUrl() }}" class="button" title="{{ __('navigation.species_list') }}">
                    @include('components.icon', ['icon' => 'list'])
                    <span class="is-hidden-tablet-only">{{ __('navigation.species_list') }}</span>
                </a>
            </div>

            <a href="{{ $species->previousUrl() }}" class="pagination-previous"{{ $species->isFirst() ? ' disabled aria-disabled="true"' : '' }}>
                &#10094;
            </a>

            <a href="{{ $species->nextUrl() }}" class="pagination-next"{{ $species->isLast() ? ' disabled aria-disabled="true"' : '' }}>
                &#10095;
            </a>
        </nav>

        <section class="mb-16">
            <div class="columns">
                <div class="column flex-center">
                    <div class="mb-8">
                        @foreach($species->ancestors as $ancestor)
                            {{ __('taxonomy.'.$ancestor->rank) }}: <b>
                                @if($ancestor->rank_level <= App\Taxon::RANKS['genus'])
                                    <i>{{ $ancestor->name }}</i>
                                @else
                                    {{ $ancestor->name }}
                                @endif
                            </b><br>
                        @endforeach
                    </div>

                    <div>
                        {{-- It's sanitized in accessor method, so don't worry ;) --}}
                        {!! $species->description !!}
                    </div>
                </div>

                <div class="column map flex-center">
                    {!! app('map.mgrs10k.basic')->render($species->mgrs10k()) !!}
                </div>
            </div>
        </section>

        @if($species->publicPhotos()->pluck('public_url')->filter()->count() > 0)
            <section class="mb-4">
                <h2 class="is-size-3 mb-2 has-text-centered">Gallery</h2>

                <nz-slider :items="{{ json_encode($species->publicPhotos()->pluck('public_url')->filter()->values()->all()) }}"></nz-slider>
            </section>
        @endif
    </div>
@endsection
