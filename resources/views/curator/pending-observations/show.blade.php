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
                    <td class="is-fullwidth">{{ $fieldObservation->taxon_name }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.date') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->year }} {{ $fieldObservation->month }} {{ $fieldObservation->day }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.photos') }}</b></td>
                    <td class="is-fullwidth">
                        <div class="columns">
                            @foreach ($fieldObservation->photos as $photo)
                                <div class="column is-one-third">
                                    <img src="{{ $photo->url }}">
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.latitude') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->latitude }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.longitude') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->longitude }}</td>
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
                    <td class="is-fullwidth">{{ $fieldObservation->observation->observer }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.identifier') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->observation->identifier }}</td>
                </tr>

                <tr>
                    <td><b>{{ __('labels.field_observations.data_license') }}</b></td>
                    <td class="is-fullwidth">{{ $fieldObservation->license_translation }}</td>
                </tr>
            </tbody>
        </table>

        @if ($fieldObservation->isPending())
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <a
                            href="{{ route('curator.pending-observations.edit', $fieldObservation) }}"
                            class="button is-primary is-outlined"
                        >{{ __('buttons.edit') }}</a>
                    </div>
                </div>

                <nz-field-observation-approval
                    :field-observation="{{ $fieldObservation }}"
                    approve-url="{{ route('api.approved-field-observations-batch.store') }}"
                    mark-as-unidentifiable-url="{{ route('api.unidentifiable-field-observations-batch.store') }}"
                    redirect-url="{{ route('curator.pending-observations.index') }}"
                />
            </div>
        @endif
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('curator.pending-observations.index') }}">{{ __('navigation.pending_observations') }}</a></li>
            <li class="is-active"><a>{{ $fieldObservation->id }}</a></li>
        </ul>
    </div>
@endsection
