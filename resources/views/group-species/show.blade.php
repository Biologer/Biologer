@extends('layouts.main', ['title' => $species->name])

@section('content')
    <div class="container pb-8 px-4 desktop:px-0">
        <nav class="pagination is-species mb-8 mt-4" role="navigation" aria-label="pagination">
            <h1 class="is-size-3 pagination-title">
                <i>{{ $species->name }}</i>&nbsp;
                @if($species->native_name)
                    <small class="is-size-6">{{ $species->native_name }}</small>
                @endif
            </h1>

            <div class="pagination-search">
                <a href="{{ route('groups.index') }}" class="button has-text-hidden-tablet-only m-1" title="{{ __('navigation.groups') }}">
                    <span class="icon has-text-grey">
                        <i class="fa fa-th"></i>
                    </span>

                    <span class="is-hidden-tablet-only">{{ __('navigation.groups') }}</span>
                </a>

                <a href="{{ $species->indexUrl() }}" class="button has-text-hidden-tablet-only m-1" title="{{ __('navigation.species_list') }}">
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
                                @if($ancestor->isGenusOrLower())
                                    <i>{{ $ancestor->name }}</i>
                                @else
                                    {{ $ancestor->name }}
                                @endif
                            </b><br>
                        @endforeach

                        @if ($descendants->isNotEmpty())
                            <div class="mt-4">
                                {{ __('taxonomy.'.$descendants->first()->rank) }}:

                                <ul>
                                    @foreach($descendants as $descendant)
                                        <li><a href="{{ route('taxa.show', $descendant) }}">
                                        @if($descendant->isGenusOrLower())
                                            <i>{{ $descendant->name }}</i>
                                        @else
                                            {{ $descendant->name }}
                                        @endif
                                        </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="mb-8">
                        {{-- For some reason checking empty on accessor result returns true even when there is a string --}}
                        {{-- This solves it --}}
                        @if (empty($description = $species->description))
                            {{ __('Text is not available in English yet.') }}
                        @else
                            {{-- It was sanitized in accessor method, so don't worry ;) --}}
                            {!! $description !!}
                        @endif
                    </div>

                    @if ($species->allochthonous || $species->invasive)
                        <div class="mb-4">
                            @if ($species->allochthonous)
                                <span class="tag">{{ __('labels.taxa.allochthonous') }}</span>
                            @endif

                            @if ($species->invasive)
                                <span class="tag">{{ __('labels.taxa.invasive') }}</span>
                            @endif
                        </div>
                    @endif

                    @if ($species->redLists->isNotEmpty())
                        <div class="mb-4">
                            <h4>{{ __('labels.taxa.red_lists') }}</h4>

                            <div class="field is-grouped is-grouped-multiline">
                                @foreach ($species->redLists as $redList)
                                    <div class="control">
                                        <div class="tags has-addons">
                                            <span class="tag">{{ $redList->name }}</span>
                                            <span class="tag is-info">{{ $redList->pivot->category }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($species->conservationLegislations->isNotEmpty())
                        <div class="mb-4">
                            <h4>{{ __('labels.taxa.conservation_legislations') }}</h4>

                            <div>
                                @foreach ($species->conservationLegislations as $conservationLegislation)
                                    <span class="tag" v-tooltip="{content: {{ json_encode($conservationLegislation->description) }}}">
                                        {{ $conservationLegislation->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($species->conservationDocuments->isNotEmpty())
                        <div class="mb-4">
                            <h4>{{ __('labels.taxa.conservation_documents') }}</h4>

                            <div>
                                @foreach ($species->conservationDocuments as $conservationDocument)
                                    <span class="tag" v-tooltip="{content: {{ json_encode($conservationDocument->description) }}}">
                                        {{ $conservationDocument->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="column map flex-center">
                    {!! app('map.mgrs10k.basic')->render($species->mgrs10k()) !!}

                    <nz-occurrence-chart
                        class="mt-8"
                        elevation-label="{{ __('Elevation') }}"
                        months-label="{{ __('Months') }}"
                        :available-stages="{{ $species->stages->pluck('name') }}"
                        :data="{{ $species->occurrence() }}"
                    />
                </div>
            </div>
        </section>

        @if($photos->isNotEmpty())
            <section class="mb-4">
                <h2 class="is-size-3 mb-2 has-text-centered">{{ __('navigation.gallery') }}</h2>

                <nz-slider :items="{{ $photos }}"></nz-slider>
            </section>
        @endif
    </div>
@endsection
