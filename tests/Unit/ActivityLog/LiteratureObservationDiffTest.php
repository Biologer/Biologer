<?php

namespace Tests\Unit\ActivityLog;

use App\ActivityLog\LiteratureObservationDiff;
use App\LiteratureObservation;
use App\ObservationIdentificationValidity;
use App\Observation;
use App\Publication;
use App\Stage;
use App\Taxon;
use Tests\TestCase;

class LiteratureObservationDiffTest extends TestCase
{
    /**
     * @test
     */
    public function logs_changes_to_general_data()
    {
        foreach ($this->generalDataProvider() as $index => $data) {
            list($attribute, $oldValue, $newValue, $expected) = $data;

            $literatureObservation = factory(LiteratureObservation::class)->create();
            $literatureObservation->observation()->save(factory(Observation::class)->make([
                $attribute => $oldValue,
            ]));

            $oldLiteratureObservation = $literatureObservation->load('observation')->replicate();

            $literatureObservation->load('observation')->observation->update([
                $attribute => $newValue,
            ]);

            $diffs = LiteratureObservationDiff::changes($literatureObservation, $oldLiteratureObservation);

            $this->assertEquals($expected, $diffs);
        }
    }

    /**
     * @test
     */
    public function logs_changes_to_specific_data()
    {
        foreach ($this->specificDataProvider() as $data) {
            list($attribute, $oldValue, $newValue, $expected) = $data;

            $literatureObservation = factory(LiteratureObservation::class)->create([
                $attribute => $oldValue,
            ]);
            $literatureObservation->observation()->save(factory(Observation::class)->make());

            $oldLiteratureObservation = $literatureObservation->load('observation')->replicate();

            $literatureObservation->load('observation')->update([
                $attribute => $newValue,
            ]);

            $diffs = LiteratureObservationDiff::changes($literatureObservation, $oldLiteratureObservation);

            $this->assertEquals($expected, $diffs);
        }
    }

    private function generalDataProvider()
    {
        yield 'Taxon using taxon_id' => (function () {
            $taxonId = factory(Taxon::class)->create(['name' => 'Old taxon'])->id;

            return [
                'taxon_id',
                $taxonId,
                factory(Taxon::class)->create(['name' => 'New taxon'])->id,
                ['taxon' => ['value' => $taxonId, 'label' => 'Old taxon']],
            ];
        })();

        yield 'Taxon using taxon_id when not set' => (function () {
            return [
                'taxon_id',
                null,
                factory(Taxon::class)->create(['name' => 'New taxon'])->id,
                ['taxon' => ['value' => null, 'label' => null]],
            ];
        })();

        yield 'Year' => [
            'year',
            2018,
            2019,
            ['year' => 2018],
        ];

        yield 'Year when not set' => [
            'year',
            null,
            2019,
            ['year' => null],
        ];

        yield 'Month' => [
            'month',
            4,
            5,
            ['month' => 4],
        ];

        yield 'Month when not set' => [
            'month',
            null,
            5,
            ['month' => null],
        ];

        yield 'Day' => [
            'day',
            4,
            5,
            ['day' => 4],
        ];

        yield 'Day when not set' => [
            'day',
            null,
            5,
            ['day' => null],
        ];

        yield 'Elevation' => [
            'elevation',
            40,
            50,
            ['elevation' => 40],
        ];

        yield 'Elevation when not set' => [
            'elevation',
            null,
            50,
            ['elevation' => null],
        ];

        yield 'Latitude' => [
            'latitude',
            40,
            50,
            ['latitude' => 40],
        ];

        yield 'Longitude' => [
            'longitude',
            40,
            50,
            ['longitude' => 40],
        ];

        yield 'Location' => [
            'location',
            40,
            50,
            ['location' => 40],
        ];

        yield 'Accuracy' => [
            'accuracy',
            4,
            10,
            ['accuracy' => 4],
        ];

        yield 'Accuracy when not set' => [
            'accuracy',
            null,
            10,
            ['accuracy' => null],
        ];

        yield 'Observer' => [
            'observer',
            'Old observer',
            'New observer',
            ['observer' => 'Old observer'],
        ];

        yield 'Observer when not set' => [
            'observer',
            null,
            'Test observer',
            ['observer' => null],
        ];

        yield 'Identifier' => [
            'identifier',
            'Old identifier',
            'New identifier',
            ['identifier' => 'Old identifier'],
        ];

        yield 'Identifier when not set' => [
            'identifier',
            null,
            'Test identifier',
            ['identifier' => null],
        ];

        yield 'Note' => [
            'note',
            'Old note',
            'Test note',
            ['note' => 'Old note'],
        ];

        yield 'Note when not set' => [
            'note',
            null,
            'New note',
            ['note' => null],
        ];

        yield 'Sex' => [
            'sex',
            'male',
            'female',
            ['sex' => ['value' => 'male', 'label' => 'labels.sexes.male']],
        ];

        yield 'Sex when not set' => [
            'sex',
            null,
            'female',
            ['sex' => ['value' => null, 'label' => null]],
        ];

        yield 'Number' => [
            'number',
            1,
            2,
            ['number' => 1],
        ];

        yield 'Number when not set' => [
            'number',
            null,
            2,
            ['number' => null],
        ];

        yield 'Project' => [
            'project',
            'Old project',
            'New project',
            ['project' => 'Old project'],
        ];

        yield 'Project when not set' => [
            'project',
            null,
            'New project',
            ['project' => null],
        ];

        yield 'Project' => [
            'found_on',
            'Old found_on',
            'New found_on',
            ['found_on' => 'Old found_on'],
        ];

        yield 'Project when not set' => [
            'found_on',
            null,
            'New found_on',
            ['found_on' => null],
        ];

        yield 'Habitat' => [
            'habitat',
            'Old habitat',
            'New habitat',
            ['habitat' => 'Old habitat'],
        ];

        yield 'Habitat when not set' => [
            'habitat',
            null,
            'New habitat',
            ['habitat' => null],
        ];

        yield 'Stage' => (function () {
            $stageId = factory(Stage::class)->create(['name' => 'egg'])->id;

            return [
                'stage_id',
                $stageId,
                factory(Stage::class)->create()->id,
                ['stage' => ['value' => $stageId, 'label' => 'stages.egg']],
            ];
        })();

        yield 'Stage when not set' => (function () {
            return [
                'stage_id',
                null,
                factory(Stage::class)->create()->id,
                ['stage' => ['value' => null, 'label' => null]],
            ];
        })();

        yield 'Dataset' => [
            'dataset',
            'Old dataset',
            'New dataset',
            ['dataset' => 'Old dataset'],
        ];

        yield 'Dataset when not set' => [
            'dataset',
            null,
            'New dataset',
            ['dataset' => null],
        ];

        yield 'Original Identification' => [
            'original_identification',
            'Maniola jurtina',
            'Aglais io',
            ['original_identification' => 'Maniola jurtina'],
        ];

        yield 'Original Identification when not set' => [
            'original_identification',
            null,
            'Maniola jurtina',
            ['original_identification' => null],
        ];
    }

    private function specificDataProvider()
    {
        yield 'Minimum elevation' => [
            'minimum_elevation',
            40,
            50,
            ['minimum_elevation' => 40],
        ];

        yield 'Minimum elevation when not set' => [
            'minimum_elevation',
            null,
            50,
            ['minimum_elevation' => null],
        ];

        yield 'Maximum elevation' => [
            'maximum_elevation',
            40,
            50,
            ['maximum_elevation' => 40],
        ];

        yield 'Maximum elevation when not set' => [
            'maximum_elevation',
            null,
            50,
            ['maximum_elevation' => null],
        ];

        yield 'Time' => [
            'time',
            '08:00',
            '09:00',
            ['time' => '08:00'],
        ];

        yield 'Time when not set' => [
            'time',
            null,
            '09:00',
            ['time' => null],
        ];

        yield 'Georeferenced by' => [
            'georeferenced_by',
            'John Doe',
            'Jane Doe',
            ['georeferenced_by' => 'John Doe'],
        ];

        yield 'Georeferenced by when not set' => [
            'georeferenced_by',
            null,
            'Jane Doe',
            ['georeferenced_by' => null],
        ];

        yield 'Georeferenced Date' => [
            'georeferenced_date',
            '2019-01-01',
            '2019-01-02',
            ['georeferenced_date' => '2019-01-01'],
        ];

        yield 'Georeferenced Date when not set' => [
            'georeferenced_date',
            null,
            '2019-01-02',
            ['georeferenced_date' => null],
        ];

        yield 'Publication' => (function () {
            $publication = factory(Publication::class)->create();

            return [
                'publication_id',
                $publication->id,
                factory(Publication::class)->create()->id,
                ['publication' => ['value' => $publication->id, 'label' => $publication->citation]],
            ];
        })();

        yield 'Is Original Data' => [
            'is_original_data',
            true,
            false,
            ['is_original_data' => ['value' => true, 'label' => 'Yes']],
        ];

        yield 'Cited Publication' => (function () {
            $publication = factory(Publication::class)->create();

            return [
                'cited_publication_id',
                $publication->id,
                factory(Publication::class)->create()->id,
                ['cited_publication' => ['value' => $publication->id, 'label' => $publication->citation]],
            ];
        })();

        yield 'Cited Publication when not set' => (function () {
            return [
                'cited_publication_id',
                null,
                factory(Publication::class)->create()->id,
                ['cited_publication' => ['value' => null, 'label' => null]],
            ];
        })();

        yield 'Place Where Referenced In Publication' => [
            'place_where_referenced_in_publication',
            'Figure 1',
            'Table 2',
            ['place_where_referenced_in_publication' => 'Figure 1'],
        ];

        yield 'Place Where Referenced In Publication when not set' => [
            'place_where_referenced_in_publication',
            null,
            'Table 2',
            ['place_where_referenced_in_publication' => null],
        ];

        yield 'Original Date' => [
            'original_date',
            '2nd of May 2018',
            '3rd of May 2018',
            ['original_date' => '2nd of May 2018'],
        ];

        yield 'Original Date when not set' => [
            'original_date',
            null,
            '3rd of May 2018',
            ['original_date' => null],
        ];

        yield 'Original Date' => [
            'original_locality',
            'Old locality',
            'New locality',
            ['original_locality' => 'Old locality'],
        ];

        yield 'Original Date when not set' => [
            'original_locality',
            null,
            'New locality',
            ['original_locality' => null],
        ];

        yield 'Original Elevation' => [
            'original_elevation',
            '30-50m',
            '35-60m',
            ['original_elevation' => '30-50m'],
        ];

        yield 'Original Elevation when not set' => [
            'original_elevation',
            null,
            '30-50m',
            ['original_elevation' => null],
        ];

        yield 'Original Coordinates' => [
            'original_coordinates',
            '20.123123,43.123123',
            '21.123123,43.123123',
            ['original_coordinates' => '20.123123,43.123123'],
        ];

        yield 'Original Coordinates when not set' => [
            'original_coordinates',
            null,
            '20.123123,43.123123',
            ['original_coordinates' => null],
        ];

        yield 'Original Identification Validity' => [
            'original_identification_validity',
            ObservationIdentificationValidity::INVALID,
            ObservationIdentificationValidity::VALID,
            [
                'original_identification_validity' => [
                    'value' => ObservationIdentificationValidity::INVALID,
                    'label' => 'labels.literature_observations.validity.invalid',
                ],
            ],
        ];
    }
}
