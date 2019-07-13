<?php

namespace Tests\Unit\ActivityLog;

use App\Stage;
use App\Taxon;
use App\License;
use Tests\TestCase;
use App\Observation;
use App\FieldObservation;
use App\ActivityLog\FieldObservationDiff;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FieldObservationDiffTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function logs_changes_to_general_data()
    {
        foreach ($this->generalDataProvider() as $index => $data) {
            list($attribute, $oldValue, $newValue, $expected) = $data;

            $FieldObservation = factory(FieldObservation::class)->create([
                'taxon_suggestion' => null,
            ]);
            $FieldObservation->observation()->save(factory(Observation::class)->make([
                $attribute => $oldValue,
            ]));

            $oldFieldObservation = $FieldObservation->load('observation')->replicate();

            $FieldObservation->load('observation')->observation->update([
                $attribute => $newValue,
            ]);

            $diffs = FieldObservationDiff::changes($FieldObservation, $oldFieldObservation);

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

            $FieldObservation = factory(FieldObservation::class)->create([
                $attribute => $oldValue,
            ]);
            $FieldObservation->observation()->save(factory(Observation::class)->make([
                'taxon_id' => null,
            ]));

            $oldFieldObservation = $FieldObservation->load('observation')->replicate();

            $FieldObservation->load('observation')->update([
                $attribute => $newValue,
            ]);

            $diffs = FieldObservationDiff::changes($FieldObservation, $oldFieldObservation);

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
    }

    private function specificDataProvider()
    {
        yield 'Taxon suggestion' => [
            'taxon_suggestion',
            'Maniola jurtina',
            'Cerambyx cerdo',
            ['taxon' => ['value' => null, 'label' => 'Maniola jurtina']],
        ];

        yield 'Taxon suggestion when not set' => [
            'taxon_suggestion',
            null,
            'Maniola jurtina',
            ['taxon' => ['value' => null, 'label' => null]],
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

        yield 'License' => [
            'license',
            License::CC_BY_SA,
            License::CC_BY_NC_SA,
            ['data_license' => ['value' => License::CC_BY_SA, 'label' => 'licenses.'.License::CC_BY_SA]],
        ];

        yield 'Unidentifiable' => [
            'unidentifiable',
            true,
            false,
            ['status' => 'unidentifiable'],
        ];

        yield 'Found dead' => [
            'found_dead',
            false,
            true,
            ['found_dead' => ['value' => false, 'label' => 'No']],
        ];

        yield 'Found dead note' => [
            'found_dead_note',
            'Test',
            'New',
            ['found_dead_note' => 'Test'],
        ];

        yield 'Found dead note when not set' => [
            'found_dead_note',
            null,
            'New',
            ['found_dead_note' => null],
        ];
    }
}
