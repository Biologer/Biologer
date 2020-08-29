<table class="table is-bordered is-narrow">
    <tbody>
        <tr>
            <td><b>{{ __('labels.collection_observations.taxon') }}</b></td>
            <td class="is-fullwidth">{{ optional($collectionObservation->observation->taxon)->name }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.date') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->year }} {{ $collectionObservation->observation->month }} {{ $collectionObservation->observation->day }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.latitude') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->latitude }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.longitude') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->longitude }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.mgrs10k') }}</b></td>
            <td class="is-fullwidth">{{ preg_replace('/^[0-9]+[a-zA-Z]/', '$0 ', $collectionObservation->observation->mgrs10k) }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.accuracy_m') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->accuracy }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.elevation_m') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.minimum_elevation_m') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->minimum_elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.maximum_elevation_m') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->maximum_elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.location') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->location }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.georeferenced_by') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->georeferenced_by }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.georeferenced_date') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->georeferenced_date }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.stage') }}</b></td>
            <td class="is-fullwidth">{{ optional($collectionObservation->observation->stage)->name_translation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.sex') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->sex_translation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.number') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->number }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.note') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->note }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.habitat') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->habitat }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.found_on') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->found_on }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.time') }}</b></td>
            <td class="is-fullwidth">{{ optional($collectionObservation->time)->format('H:i') }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.project') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->project }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.dataset') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->dataset }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.observer') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->observer }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.identifier') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->identifier }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collection') }}</b></td>
            <td class="is-fullwidth">{{ optional($collectionObservation->collection)->name }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.original_date') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->original_date }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.original_locality') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->original_locality }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.original_elevation') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->original_elevation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.original_coordinates') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->original_coordinates }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.original_identification') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->observation->original_identification }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.original_identification_validity') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->original_identification_validity_translation }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.verbatim_tag') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->verbatim_tag }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.photos') }}</b></td>
            <td class="is-fullwidth">
                <div class="columns">
                    @foreach ($collectionObservation->photos as $photo)
                        <div class="column is-one-third">
                            <img src="{{ "{$photo->url}?v={$photo->updated_at->timestamp}" }}">
                        </div>
                    @endforeach
                </div>
            </td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collecting_start_year') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->collecting_start_year }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collecting_start_month') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->collecting_start_month }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collecting_end_year') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->collecting_end_year }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collecting_end_month') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->collecting_end_month }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collecting_method') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->collecting_method }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.collector') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->collector }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.catalogue_number') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->catalogue_number }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.cabinet_number') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->cabinet_number }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.box_number') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->box_number }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.disposition') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->disposition }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.preparator') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->preparator }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.preparation_method') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->preparation_method }}</td>
        </tr>

        <tr>
            <td><b>{{ __('labels.collection_observations.type_status') }}</b></td>
            <td class="is-fullwidth">{{ $collectionObservation->type_status }}</td>
        </tr>
    </tbody>
</table>
