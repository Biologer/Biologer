<table class="table is-bordered is-narrow">
    <tbody>
        <tr>
            <td><b>{{ __('labels.literature_observations.taxon') }}</b></td>
            <td class="is-fullwidth">{{ optional($literatureObservation->observation->taxon)->name }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.date') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->year }} {{ $literatureObservation->observation->month }} {{ $literatureObservation->observation->day }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.latitude') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->latitude }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.longitude') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->longitude }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.mgrs10k') }}</b></td>
            <td class="is-fullwidth">{{ preg_replace('/^[0-9]+[a-zA-Z]/', '$0 ', $literatureObservation->observation->mgrs10k) }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.accuracy_m') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->accuracy }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.elevation_m') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.minimum_elevation_m') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->minimum_elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.maximum_elevation_m') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->maximum_elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.location') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->location }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.georeferenced_by') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->georeferenced_by }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.georeferenced_date') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->georeferenced_date }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.stage') }}</b></td>
            <td class="is-fullwidth">{{ optional($literatureObservation->observation->stage)->name_translation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.sex') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->sex_translation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.number') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->number }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.note') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->note }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.habitat') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->habitat }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.found_on') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->found_on }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.time') }}</b></td>
            <td class="is-fullwidth">{{ optional($literatureObservation->time)->format('H:i') }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.project') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->project }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.dataset') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->dataset }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.observer') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->observer }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.identifier') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->identifier }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.publication') }}</b></td>
            <td class="is-fullwidth">{{ optional($literatureObservation->publication)->citation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.is_original_data') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->is_original_data ? __('Yes') : __('No') }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.cited_publication') }}</b></td>
            <td class="is-fullwidth">{{ optional($literatureObservation->is_original_data ? null : $literatureObservation->citedPublication)->citation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.place_where_referenced_in_publication') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->place_where_referenced_in_publication }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.original_date') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->original_date }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.original_locality') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->original_locality }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.original_elevation') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->original_elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.original_coordinates') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->original_coordinates }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.original_identification') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->observation->original_identification }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.literature_observations.original_identification_validity') }}</b></td>
            <td class="is-fullwidth">{{ $literatureObservation->original_identification_validity_translation }}</td>
        </tr>
    </tbody>
</table>
