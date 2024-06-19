@extends('layouts.main', ['title' => $species->name])

@section('content')
    <div class="container pb-8 px-4 desktop:px-0">
        <nav class="pagination is-species mb-8 mt-4" role="navigation" aria-label="pagination">
            <div>
                <h1 class="is-size-3">
                    <i>{{ $species->name }}</i>
                </h1>
                <h2 class="is-size-4">
                    {{ $species->author }}
                </h2>

                @if($species->native_name)

                    <h3 class="is-size-6">{{ $species->native_name }}</h3>

                @endif
            </div>

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
                    <span class="icon has-text-grey" aria-hidden="true">
                        <i class="fa fa-chevron-left"></i>
                    </span>

                    <span class="is-sr-only">{{ __('pagination.previous') }}</span>
                </span>
            @else
                <a href="{{ $species->previousUrl() }}" class="pagination-previous" title="{{ __('pagination.next') }}">
                    <span class="icon has-text-grey" aria-hidden="true">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                </a>
            @endif


            @if($species->isLast())
                <span class="pagination-next" disabled aria-disabled="true">
                    <span class="icon has-text-grey" aria-hidden="true">
                        <i class="fa fa-chevron-right"></i>
                    </span>

                    <span class="is-sr-only">{{ __('pagination.next') }}</span>
                </span>
            @else
                <a href="{{ $species->nextUrl() }}" class="pagination-next" title="{{ __('pagination.next') }}">
                    <span class="icon has-text-grey" aria-hidden="true">
                        <i class="fa fa-chevron-right"></i>
                    </span>
                </a>
            @endif
        </nav>

        <section class="mb-16">
            <div class="columns">
                <div class="column flex-center">
                    <div class="mb-8">
                        <h3 class="subtitle is-size-7 is-italic has-text-grey">
                            @foreach($species->synonyms as $synonym)
                                {{ $synonym->name }}, {{$synonym->author}}
                                @if(!$loop->last)
                                    {{";"}}
                                @endif
                            @endforeach
                        </h3>

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

                    <table class="is-sr-only">
                        <caption>{{ __('pages.taxa.number_of_observations_per_mgrs10k_field') }} }}</caption>

                        <thead>
                            <tr>
                                <th>{{ __('pages.taxa.mgrs10k_field') }}</th>
                                <th>{{ __('pages.taxa.number_of_observations') }}</th>
                                <th>{{ __('pages.taxa.present_in_literature') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($species->mgrs10k() as $field => $fieldData)
                                <tr>
                                    <td>{{ $field }}</td>
                                    <td>{{ $fieldData['observations_count'] }}</td>
                                    <td>{{ $fieldData['present_in_literature'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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
