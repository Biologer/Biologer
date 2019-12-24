<?php

namespace Tests\Feature\Api;

use App\CollectionObservation;
use App\ObservationIdentificationValidity;
use App\Observation;
use App\SpecimenCollection;
use App\Stage;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateCollectionObservationTest extends TestCase
{
    /** @test */
    public function guest_cannot_update_observation()
    {
        $collectionObservation = $this->createCollectionObservation();

        $response = $this->putJson("/api/collection-observations/{$collectionObservation->id}", $this->validParams());

        $response->assertUnauthorized();
    }

    /** @test */
    public function unauthorized_user_cannot_update_observation()
    {
        $collectionObservation = $this->createCollectionObservation();

        Passport::actingAs(factory(User::class)->create());
        $response = $this->putJson("/api/collection-observations/{$collectionObservation->id}", $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function curator_can_update_collection_observations()
    {
        $this->seed('RolesTableSeeder');
        $collectionObservation = $this->createCollectionObservation();
        $taxon = factory(Taxon::class)->create();
        $stage = factory(Stage::class)->create();
        $specimenCollection = factory(SpecimenCollection::class)->create();

        Passport::actingAs(factory(User::class)->create()->assignRoles('curator'));
        $response = $this->withoutExceptionHandling()->putJson("/api/collection-observations/{$collectionObservation->id}", $this->validParams([
            'taxon_id' => $taxon->id,
            'stage_id' => $stage->id,
            'collection_id' => $specimenCollection->id,
        ]));

        $response->assertSuccessful();
        $collectionObservation = $collectionObservation->refresh();
        $this->assertEquals('May 13 1991', $collectionObservation->original_date);
        $this->assertEquals('Balkan Mountain', $collectionObservation->original_locality);
        $this->assertEquals('32-50m', $collectionObservation->original_elevation);
        $this->assertEquals('21°22\'44",41°21\'35"', $collectionObservation->original_coordinates);
        $this->assertEquals('Testuduo hermanii', $collectionObservation->observation->original_identification);
        $this->assertEquals(ObservationIdentificationValidity::INVALID, $collectionObservation->original_identification_validity);
        $this->assertTrue($collectionObservation->observation->taxon->is($taxon));
        $this->assertEquals(1991, $collectionObservation->observation->year);
        $this->assertEquals(5, $collectionObservation->observation->month);
        $this->assertEquals(13, $collectionObservation->observation->day);
        $this->assertTrue($collectionObservation->collection->is($specimenCollection));
        $this->assertEquals(22.123123, $collectionObservation->observation->latitude);
        $this->assertEquals(44.123123, $collectionObservation->observation->longitude);
        $this->assertEquals('Balkan Mountain', $collectionObservation->observation->location);
        $this->assertEquals(20, $collectionObservation->observation->accuracy);
        $this->assertEquals(37, $collectionObservation->observation->elevation);
        $this->assertEquals(32, $collectionObservation->minimum_elevation);
        $this->assertEquals(50, $collectionObservation->maximum_elevation);
        $this->assertEquals('Scooby Doo', $collectionObservation->georeferenced_by);
        $this->assertEquals(now()->subDay()->toDateString(), $collectionObservation->georeferenced_date->toDateString());
        $this->assertEquals('female', $collectionObservation->observation->sex);
        $this->assertTrue($collectionObservation->observation->stage->is($stage));
        $this->assertEquals('Some other information', $collectionObservation->other_original_data);
        $this->assertEquals(1990, $collectionObservation->collecting_start_year);
        $this->assertEquals(3, $collectionObservation->collecting_start_month);
        $this->assertEquals(1990, $collectionObservation->collecting_end_year);
        $this->assertEquals(6, $collectionObservation->collecting_end_month);
    }

    protected function validParams($overrides = [])
    {
        return array_map('value', array_merge([
            'original_date' => 'May 13 1991',
            'original_locality' => 'Balkan Mountain',
            'original_elevation' => '32-50m',
            'original_coordinates' => '21°22\'44",41°21\'35"',
            'original_identification' => 'Testuduo hermanii',
            'original_identification_validity' => ObservationIdentificationValidity::INVALID,
            'other_original_data' => 'Some other information',
            'collecting_start_year' => 1990,
            'collecting_start_month' => 3,
            'collecting_end_year' => 1990,
            'collecting_end_month' => 6,
            'taxon_id' => function () {
                return factory(Taxon::class)->create()->id;
            },
            'year' => 1991,
            'month' => 5,
            'day' => 13,
            'collection_id' => function () {
                return factory(SpecimenCollection::class)->create()->id;
            },
            'latitude' => 22.123123,
            'longitude' => 44.123123,
            'location' => 'Balkan Mountain',
            'accuracy' => 20,
            'elevation' => 37,
            'minimum_elevation' => 32,
            'maximum_elevation' => 50,
            'georeferenced_by' => 'Scooby Doo',
            'georeferenced_date' => now()->subDay()->toDateString(),
            'reason' => 'Test',
            'sex' => 'female',
            'stage_id' => function () {
                return factory(Stage::class)->create()->id;
            },
        ], $overrides));
    }

    protected function createCollectionObservation()
    {
        $collectionObservation = factory(CollectionObservation::class)->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => ObservationIdentificationValidity::VALID,
            'other_original_data' => 'Some information',
            'collection_id' => factory(SpecimenCollection::class)->create()->id,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
        ]);

        $collectionObservation->observation()->save(factory(Observation::class)->make([
            'original_identification' => 'Testudo hermanii',
            'taxon_id' => factory(Taxon::class)->create()->id,
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'latitude' => 21.123123,
            'longitude' => 43.123123,
            'location' => 'Gledić Mountains',
            'accuracy' => 10,
            'elevation' => 370,
            'created_by_id' => factory(User::class)->create()->id,
            'sex' => 'male',
            'stage_id' => factory(Stage::class)->create(['name' => 'Old stage'])->id,
        ]));

        return $collectionObservation;
    }
}
