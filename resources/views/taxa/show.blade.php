@extends('layouts.main', ['title' => $taxon->name])

@section('content')
    <div class="container pb-8 px-4 desktop:px-0">
        <nav class="pagination is-species mb-8 mt-4" role="navigation" aria-label="pagination">
            <h1 class="is-size-3 pagination-title">
                @if($taxon->isGenusOrLower())
                    <i>{{ $taxon->name }}</i>
                @else
                    {{ $taxon->name }}
                @endif
                &nbsp;

                @if($taxon->native_name)
                    <small class="is-size-6">{{ $taxon->native_name }}</small>
                @endif
            </h1>

            <div class="pagination-search">
                <a href="{{ route('groups.index') }}" class="button has-text-hidden-tablet-only m-1" title="{{ __('navigation.groups') }}">
                    <span class="icon has-text-grey">
                        <i class="fa fa-th"></i>
                    </span>

                    <span class="is-hidden-tablet-only">{{ __('navigation.groups') }}</span>
                </a>
            </div>
        </nav>

        <section class="mb-16">
            <div class="columns">
                <div class="column flex-center">
                    <div class="mb-8">
                        @foreach($taxon->ancestors as $ancestor)
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
                        @if (empty($description = $taxon->description))
                            {{ __('Text is not available in English yet.') }}
                        @else
                            {{-- It was sanitized in accessor method, so don't worry ;) --}}
                            {!! $description !!}
                        @endif
                    </div>

                    @if ($taxon->allochthonous || $taxon->invasive)
                        <div class="mb-4">
                            @if ($taxon->allochthonous)
                                <span class="tag">{{ __('labels.taxa.allochthonous') }}</span>
                            @endif

                            @if ($taxon->invasive)
                                <span class="tag">{{ __('labels.taxa.invasive') }}</span>
                            @endif
                        </div>
                    @endif

                    @if ($taxon->redLists->isNotEmpty())
                        <div class="mb-4">
                            <h4>{{ __('labels.taxa.red_lists') }}</h4>

                            <div class="field is-grouped is-grouped-multiline">
                                @foreach ($taxon->redLists as $redList)
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

                    @if ($taxon->conservationLegislations->isNotEmpty())
                        <div class="mb-4">
                            <h4>{{ __('labels.taxa.conservation_legislations') }}</h4>

                            <div>
                                @foreach ($taxon->conservationLegislations as $conservationLegislation)
                                    <span class="tag" v-tooltip="{content: {{ json_encode($conservationLegislation->description) }}}">
                                        {{ $conservationLegislation->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($taxon->conservationDocuments->isNotEmpty())
                        <div class="mb-4">
                            <h4>{{ __('labels.taxa.conservation_documents') }}</h4>

                            <div>
                                @foreach ($taxon->conservationDocuments as $conservationDocument)
                                    <span class="tag" v-tooltip="{content: {{ json_encode($conservationDocument->description) }}}">
                                        {{ $conservationDocument->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="column map flex-center">
                    {!! app('map.mgrs10k.basic')->render($taxon->mgrs10k()) !!}

                    <table class="is-sr-only">
                        <caption>{{ __('pages.taxa.number_of_observations_per_mgrs10k_field') }}</caption>

                        <thead>
                            <tr>
                                <th>{{ __('pages.taxa.mgrs10k_field') }}</th>
                                <th>{{ __('pages.taxa.number_of_observations') }}</th>
                                <th>{{ __('pages.taxa.present_in_literature') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($taxon->mgrs10k() as $field => $fieldData)
                                <tr>
                                    <td>{{ $field }}</td>
                                    <td>{{ $fieldData['observations_count'] }}</td>
                                    <td>{{ $fieldData['present_in_literature'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($taxon->isGenusOrLower())
                        <nz-occurrence-chart
                            class="mt-8"
                            elevation-label="{{ __('Elevation') }}"
                            months-label="{{ __('Months') }}"
                            :available-stages="{{ $taxon->stages->pluck('name') }}"
                            :data="{{ $taxon->occurrence() }}"
                        />
                    @endif
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
