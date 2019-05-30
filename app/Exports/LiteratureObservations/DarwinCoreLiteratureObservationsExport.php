<?php

namespace App\Exports\LiteratureObservations;

use App\Taxon;
use App\Export;
use App\Exports\BaseExport;
use App\LiteratureObservation;
use Illuminate\Support\Carbon;

class DarwinCoreLiteratureObservationsExport extends BaseExport
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
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function query(Export $export)
    {
        return LiteratureObservation::with([
            'observation.taxon.ancestors', 'observation.stage', 'activity',
            'publication', 'citedPublication',
        ])->filter($export->filter)->orderBy('id');
    }

    /**
     * Extract needed data from item.
     *
     * @param  \App\LiteratureObservation  $item
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
            'basisOfRecord' => '', // HumanObservation
            'dcterms:type' => 'Text',
            'dcterms:rightsHolder' => '',
            'dcterms:accessRights' => '',
            'dcterms:license' => '',
            'minimumElevationInMeters' => $item->observation->elevation,
            'maximumElevationInMeters' => $item->observation->elevation,
            'recordNumber' => $item->observation->id,
            'verbatimLocality' => $item->origina_locality,
            'verbatimEventDate' => $item->original_date,
            'verbatimElevation' => $item->original_elevation,
            'verbatimCoordinates' => $item->original_coordinates,
            'sex' => $item->observation->sex,
            'lifeStage' => optional($item->observation->stage)->name,
            'associatedMedia' => '',
            'locality' => $item->observation->location,
            'geodeticDatum' => 'WGS84',
            'decimalLatitude' => $item->observation->latitude,
            'decimalLongitude' => $item->observation->longitude,
            'coordinateUncertaintyInMeters' => $item->observation->accuracy,
            'georeferencedBy' => $item->georeferenced_by ?: $item->observation->observer,
            'georeferencedDate' => optional($item->georeferenced_date)->toIso8601String(),
            'georeferenceProtocol' => '',
            'individualCount' => $item->observation->number,
            'recordedBy' => $item->observation->observer,
            'eventDate' => $item->observation->isDateComplete()
                ? Carbon::create($item->observation->year, $item->observation->month, $item->observation->day)->toDateString()
                : '',
            'eventTime' => optional($item->time)->format('H:i'),
            'year' => $item->observation->year,
            'month' => $item->observation->month,
            'day' => $item->observation->day,

            'ReferenceID' => $item->publication_id,
            'ReferenceType' => $item->publication->type()->toDarwinCore(),
            'ReferenceAuthors' => $item->publication->authors->implode('|'),
            'ReferenceEditors' => $item->publication->editors->implode('|'),
            'ReferenceYear' => $item->publication->year,
            'ReferencePage' => $item->publication->page_range,
            'ReferencePages' => $item->publication->page_count,
            'ReferenceTitle' => $item->publication->title,
            'ReferencePublicationTitle' => $item->publication->name,
            'ReferencePublisher' => $item->publication->publisher,
            'ReferencePlace' => $item->publication->place,
            'ReferenceDOI' => $item->publication->doi,
            'ReferenceLink' => $item->publication->link,
            'ReferencePdf' => optional($item->publication->attachment)->url,
            'Reference' => $item->publication->citation,
        ];
    }

    /**
     * Get name of anacestor of given rank.
     *
     * @param  \App\Taxon|null  $taxon
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
     * @param  \App\Taxon|null  $taxon
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
     * @param  \App\Taxon|null  $taxon
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
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return string
     */
    private function firstIdentification($literatureObservation)
    {
        $activity = $literatureObservation->activity->last(function ($activity) {
            return $activity->changes()->collect('old')->has('taxon');
        });

        if ($activity) {
            return $activity->changes()->collect('old')->get('taxon');
        }

        $taxon = $literatureObservation->observation->taxon;

        return $taxon ? $taxon->name : '';
    }

    private function previousIdentifications($literatureObservation)
    {
        return $literatureObservation->activity->map(function ($activity) {
            return $activity->changes()->collect('old')->get('taxon');
        })->filter()->implode('|');
    }

    private function identifiedAt($literatureObservation)
    {
        if (! $literatureObservation->observation->taxon) {
            return;
        }

        $activity = $literatureObservation->activity->first(function ($activity) {
            return $activity->changes()->collect('old')->has('taxon');
        });

        if ($activity) {
            return $activity->created_at;
        }

        return $literatureObservation->created_at;
    }

    private function getTaxonNativeName($taxon, $locale)
    {
        if (! $taxon) {
            return '';
        }

        return $taxon->translateOrNew($locale)->name;
    }
}
