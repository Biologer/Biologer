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
                <a href="{{ route('groups.index') }}" class="button has-text-hidden-tablet-only" title="{{ __('navigation.groups') }}">
                    <span class="icon has-text-grey">
                        <i class="fa fa-th"></i>
                    </span>

                    <span class="is-hidden-tablet-only">{{ __('navigation.groups') }}</span>
                </a>

                <a href="{{ $species->indexUrl() }}" class="button has-text-hidden-tablet-only" title="{{ __('navigation.species_list') }}">
                    <span class="icon has-text-grey">
                        <i class="fa fa-list"></i>
                    </span>

                    <span class="is-hidden-tablet-only">{{ __('navigation.species_list') }}</span>
                </a>

                <nz-group-taxa-search-button :group="{{ $species->group()->id }}" />
            </div>

            @if($species->isFirst())
                <span class="pagination-previous" disabled aria-disabled="true">
                    &#10094;
                </span>
            @else
                <a href="{{ $species->previousUrl() }}" class="pagination-previous">
                    &#10094;
                </a>
            @endif


            @if($species->isLast())
                <span class="pagination-next" disabled aria-disabled="true">
                    &#10095;
                </span>
            @else
                <a href="{{ $species->nextUrl() }}" class="pagination-next">
                    &#10095;
                </a>
            @endif
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
                <h2 class="is-size-3 mb-2 has-text-centered">{{ __('navigation.gallery') }}</h2>

                <nz-slider :items="{{ json_encode($species->publicPhotos()->pluck('public_url')->filter()->values()->all()) }}"></nz-slider>
            </section>
        @endif
    </div>
@endsection
