<?php

namespace Tests\Unit\ActivityLog;

use App\ActivityLog\FieldObservationDiff;
use App\FieldObservation;
use App\License;
use App\Observation;
use App\Stage;
use App\Taxon;
use Tests\TestCase;

class FieldObservationDiffTest extends TestCase
{
    /**
     * @test
     */
    public function logs_changes_to_general_data()
    {
        foreach ($this->generalDataProvider() as $index => $data) {
            list($attribute, $oldValue, $newValue, $expected) = $data;

            $fieldObservation = FieldObservation::factory()->make([
                'taxon_suggestion' => null,
            ]);
            $fieldObservation->setRelation('observation', Observation::factory()->make([
                $attribute => $oldValue,
            ]));

            $oldFieldObservation = $fieldObservation->replicate()->setRelation(
                'observation',
                $fieldObservation->observation->replicate()
            );

            $fieldObservation->observation->fill([
                $attribute => $newValue,
            ]);

            $diffs = FieldObservationDiff::changes($fieldObservation, $oldFieldObservation);

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

            $fieldObservation = FieldObservation::factory()->make([
                $attribute => $oldValue,
            ]);
            $fieldObservation->setRelation('observation', Observation::factory()->make([
                'taxon_id' => null,
            ]));

            $oldFieldObservation = $fieldObservation->replicate()->setRelation(
                'observation',
                $fieldObservation->observation->replicate()
            );

            $fieldObservation->fill([
                $attribute => $newValue,
            ]);

            $diffs = FieldObservationDiff::changes($fieldObservation, $oldFieldObservation);

            $this->assertEquals($expected, $diffs);
        }
    }

    private function generalDataProvider()
    {
        $taxonId = Taxon::factory()->create(['name' => 'Old taxon'])->id;
        $newTaxonId = Taxon::factory()->create(['name' => 'New taxon'])->id;

        yield 'Taxon using taxon_id' => [
            'taxon_id',
            $taxonId,
            $newTaxonId,
            ['taxon' => ['value' => $taxonId, 'label' => 'Old taxon']],
        ];

        yield 'Taxon using taxon_id when not set' => [
            'taxon_id',
            null,
            $newTaxonId,
            ['taxon' => ['value' => null, 'label' => null]],
        ];

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

        $stageId = Stage::factory()->create(['name' => 'egg'])->id;
        $newStageId = Stage::factory()->create()->id;

        yield 'Stage' => [
            'stage_id',
            $stageId,
            $newStageId,
            ['stage' => ['value' => $stageId, 'label' => 'stages.egg']],
        ];

        yield 'Stage when not set' => [
            'stage_id',
            null,
            $newStageId,
            ['stage' => ['value' => null, 'label' => null]],
        ];

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
