<?php

namespace App\Exports\FieldObservations;

use App\Models\Export;
use App\Exports\BaseExport;
use App\Models\FieldObservation;
use App\Models\Taxon;

class DarwinCoreFieldObservationsExport extends BaseExport
{
    public static function createFiltered($filters = [])
    {
        return static::create(static::availableColumns()->all(), $filters, true);
    }

    /**
     * Column labels and names.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function columnData()
    {
        return collect([
            'occurrenceID',
            'taxonID',
            'kingdom',
            'phylum',
            'class',
            'order',
            'family',
            'genus',
            'specificEpithet',
            'species',
            'scientificNameAuthorship',
            'infraspecificEpithet',
            'scientificName',
            'acceptedNameUsage',
            'previousIdentifications',
            'taxonRank',
            'vernacularName',
            'vernacularNameSerbian',
            'vernacularNameSerbian_latin',
            'vernacularNameCroatian',
            'taxonomicStatus',
            'identifiedBy',
            'dateIdentified',
            'basisOfRecord',
            'dcterms:type',
            'typeOfRecord',
            'dcterms:rightsHolder',
            'dcterms:accessRights',
            'dcterms:license',
            'minimumElevationInMeters',
            'maximumElevationInMeters',
            'recordNumber',
            'verbatimLocality',
            'verbatimEventDate',
            'verbatimElevation',
            'verbatimCoordinates',
            'sex',
            'lifeStage',
            'associatedMedia',
            'locality',
            'geodeticDatum',
            'decimalLatitude',
            'decimalLongitude',
            'coordinateUncertaintyInMeters',
            'georeferencedBy',
            'georeferencedDate',
            'georeferenceProtocol',
            'individualCount',
            'recordedBy',
            'eventDate',
            'eventTime',
            'year',
            'month',
            'day',

            'ReferenceID',
            'ReferenceType',
            'ReferenceAuthors',
            'ReferenceEditors',
            'ReferenceYear',
            'ReferencePage',
            'ReferencePages',
            'ReferenceTitle',
            'ReferencePublicationTitle',
            'ReferencePublisher',
            'ReferencePlace',
            'ReferenceDOI',
            'ReferenceLink',
            'ReferencePdf',
            'Reference',
        ])->map(function ($column) {
            return [
                'label' => $column,
                'value' => $column,
            ];
        });
    }

    /**
     * Database query to get the data for export.
     *
     * @param  \App\Models\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function query(Export $export)
    {
        return FieldObservation::with([
            'observation.taxon.ancestors', 'observation.photos', 'observedBy',
            'identifiedBy', 'observation.types.translations', 'observation.stage',
            'activity',
        ])->filter($export->filter)->orderBy('id');
    }

    /**
     * Extract needed data from item.
     *
     * @param  \App\Models\FieldObservation  $item
     * @return array
     */
    protected function transformItem($item)
    {
        $taxon = $item->observation->taxon;

        return [
            'occurrenceID' => $item->observation->id,
            'taxonID' => $item->observation->taxon_id,
            'kingdom' => $this->getTaxonOfRank($taxon, 'kingdom'),
            'phylum' => $this->getTaxonOfRank($taxon, 'phylum'),
            'class' => $this->getTaxonOfRank($taxon, 'class'),
            'order' => $this->getTaxonOfRank($taxon, 'order'),
            'family' => $this->getTaxonOfRank($taxon, 'family'),
            'genus' => $this->getTaxonOfRank($taxon, 'genus'),
            'specificEpithet' => $this->extractSpeciesEpithet($taxon),
            'species' => $this->getTaxonOfRank($taxon, 'species'),
            'scientificNameAuthorship' => optional($taxon)->author,
            'infraspecificEpithet' => $this->extractInfraspeciesEpithet($taxon),
            'scientificName' => $this->firstIdentification($item),
            'acceptedNameUsage' => implode(' ', array_filter([optional($taxon)->name, optional($taxon)->author])),
            'previousIdentifications' => $this->previousIdentifications($item),
            'taxonRank' => optional($taxon)->rank,
            'vernacularName' => $this->getTaxonNativeName($taxon, 'en'),
            'vernacularNameSerbian' => $this->getTaxonNativeName($taxon, 'sr'),
            'vernacularNameSerbian_latin' => $this->getTaxonNativeName($taxon, 'sr-Latn'),
            'vernacularNameCroatian' => $this->getTaxonNativeName($taxon, 'hr'),
            'taxonomicStatus' => $taxon ? 'valid' : '',
            'identifiedBy' => $taxon ? $item->observation->identifier : '',
            'dateIdentified' => optional($this->identifiedAt($item))->toIso8601String(),
            'basisOfRecord' => 'HumanObservation',
            'dcterms:type' => $item->observation->photos->isNotEmpty() ? 'StillImage' : 'Event',
            'typeOfRecord' => $item->observation->types->map(function ($type) {
                return $type->translations->where('locale', 'en')->first();
            })->pluck('name')->filter()->implode('|'),
            'dcterms:rightsHolder' => config('app.name'),
            'dcterms:accessRights' => optional($item->license())->name,
            'dcterms:license' => optional($item->license())->link,
            'minimumElevationInMeters' => $item->observation->elevation,
            'maximumElevationInMeters' => $item->observation->elevation,
            'recordNumber' => $item->observation->id,
            'verbatimLocality' => '',
            'verbatimEventDate' => '',
            'verbatimElevation' => '',
            'verbatimCoordinates' => '',
            'sex' => $item->observation->sex,
            'lifeStage' => optional($item->observation->stage)->name,
            'associatedMedia' => $item->observation->photos->pluck('public_url')->filter()->implode('|'),
            'locality' => $item->observation->location,
            'geodeticDatum' => 'WGS84',
            'decimalLatitude' => $item->observation->latitude,
            'decimalLongitude' => $item->observation->longitude,
            'coordinateUncertaintyInMeters' => $item->observation->accuracy,
            'georeferencedBy' => $item->observation->observer,
            'georeferencedDate' => $item->observation->created_at->toIso8601String(),
            'georeferenceProtocol' => '',
            'individualCount' => $item->observation->number,
            'recordedBy' => $item->observation->observer,
            'eventDate' => '',
            'eventTime' => optional($item->time)->format('H:i'),
            'year' => $item->observation->year,
            'month' => $item->observation->month,
            'day' => $item->observation->day,

            'ReferenceID' => '',
            'ReferenceType' => '',
            'ReferenceAuthors' => '',
            'ReferenceEditors' => '',
            'ReferenceYear' => '',
            'ReferencePage' => '',
            'ReferencePages' => '',
            'ReferenceTitle' => '',
            'ReferencePublicationTitle' => '',
            'ReferencePublisher' => '',
            'ReferencePlace' => '',
            'ReferenceDOI' => '',
            'ReferenceLink' => '',
            'ReferencePdf' => '',
            'Reference' => '',
        ];
    }

    /**
     * Get name of anacestor of given rank.
     *
     * @param  \App\Models\Taxon|null  $taxon
     * @param  string  $rank
     * @return string
     */
    private function getTaxonOfRank($taxon, $rank)
    {
        if (! $taxon || $taxon->rank_level > Taxon::RANKS[$rank]) {
            return '';
        }

        if ($taxon->rank === $rank) {
            return $taxon->name;
        }

        $rankTaxon = $taxon->ancestors->where('rank', $rank)->first();

        return $rankTaxon ? $rankTaxon->name : '';
    }

    /**
     * Extract infraspecies epithet.
     *
     * @param  \App\Models\Taxon|null  $taxon
     * @return string
     */
    private function extractInfraspeciesEpithet($taxon)
    {
        if (! $taxon || $taxon->rank_level >= Taxon::RANKS['species']) {
            return '';
        }

        $value = explode(' ', $taxon->name);

        unset($value[0], $value[1]);

        return implode(' ', $value);
    }

    /**
     * Extract species epithet.
     *
     * @param  \App\Models\Taxon|null  $taxon
     * @return string
     */
    private function extractSpeciesEpithet($taxon)
    {
        if (! $taxon || $taxon->rank_level >= Taxon::RANKS['genus']) {
            return '';
        }

        $value = explode(' ', $taxon->name);

        return $value[1] ?? '';
    }

    /**
     * Get name of the first identification.
     *
     * @param  \App\Models\FieldObservation  $fieldObservation
     * @return string
     */
    private function firstIdentification($fieldObservation)
    {
        $activity = $fieldObservation->activity->last(function ($activity) {
            return $activity->changes()->getCollect('old')->has('taxon');
        });

        if ($activity) {
            return $this->getTaxonNameFromActivity($activity);
        }

        $taxon = $fieldObservation->observation->taxon;

        return $taxon ? $taxon->name : '';
    }

    private function getTaxonNameFromActivity($activity)
    {
        $taxon = $activity->changes()->getCollect('old')->get('taxon');

        return is_array($taxon) ? $taxon['label'] : $taxon;
    }

    private function previousIdentifications($fieldObservation)
    {
        return $fieldObservation->activity->map(function ($activity) {
            return $this->getTaxonNameFromActivity($activity);
        })->filter()->implode('|');
    }

    private function identifiedAt($fieldObservation)
    {
        if (! $fieldObservation->observation->taxon) {
            return;
        }

        $activity = $fieldObservation->activity->first(function ($activity) {
            return $activity->changes()->getCollect('old')->has('taxon');
        });

        if ($activity) {
            return $activity->created_at;
        }

        return $fieldObservation->created_at;
    }

    private function getTaxonNativeName($taxon, $locale)
    {
        if (! $taxon) {
            return '';
        }

        return $taxon->translateOrNew($locale)->name;
    }
}
