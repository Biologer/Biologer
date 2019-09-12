@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        <table class="table is-bordered is-narrow">
            <tbody>
                <tr>
                    <td><b>{{ __('labels.field_observations.status') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->status_translation }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.taxon') }}</b></td>
                    <td class="is-fullwidth">{{ optional($fieldObservation->observation->taxon)->name }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.date') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->year }} {{ $fieldObservation->observation->month }} {{ $fieldObservation->observation->day }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.photos') }}</b></td>
                    <td class="is-fullwidth">
                        <div class="columns">
                            @foreach ($fieldObservation->photos->filter->public_url as $photo)
                                <div class="column is-one-third">
                                    <img src="{{ "{$photo->public_url}?v={$photo->updated_at->timestamp}" }}">
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.latitude') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->shouldHideRealCoordinates() ? __('N/A') : $fieldObservation->observation->latitude }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.longitude') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->shouldHideRealCoordinates() ? __('N/A') : $fieldObservation->observation->longitude }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.mgrs10k') }}</b></td>
                    <td class="is-fullwidth">{{ preg_replace('/^[0-9]+[a-zA-Z]/', '$0 ', $fieldObservation->observation->mgrs10k) }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.accuracy_m') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->accuracy }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.elevation_m') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->elevation }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.location') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->location }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.stage') }}</b></td>
                    <td class="is-fullwidth">{{ optional($fieldObservation->observation->stage)->name_translation }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.sex') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->sex_translation }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.types') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->types->pluck('name')->filter()->implode(', ') }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.number') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->number }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.note') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->note }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.habitat') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->habitat }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.found_on') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->found_on }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.time') }}</b></td>
                    <td class="is-fullwidth">{{ optional($fieldObservation->time)->format('H:i') }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.project') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->project }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.dataset') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->dataset }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.found_dead') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->found_dead ? __('Yes') : __('No') }}</td>
                </tr>

                @if ($fieldObservation->found_dead)
                    <tr>
                        <td><b>{{ __('labels.field_observations.found_dead_note') }}</b></td>
                        <td class="is-fullwidth">{{ $fieldObservation->found_dead_note }}</td>
                    </tr>
                @endif

                <tr>
                    <td><b>{{ __('labels.field_observations.observer') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observer }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.identifier') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->identifier }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.data_license') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->license_translation }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.submitted_using') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->client_name ?? __('Unknown') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('contributor.public-field-observations.index') }}">{{ __('navigation.public_field_observations') }}</a></li>
            <li class="is-active"><a>{{ $fieldObservation->id }}</a></li>
        </ul>
    </div>
@endsection
