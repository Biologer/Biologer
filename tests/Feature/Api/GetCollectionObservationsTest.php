<?php

namespace Tests\Feature\Api;

use App\CollectionObservation;
use App\Observation;
use App\ObservationIdentificationValidity;
use App\SpecimenCollection;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetCollectionObservationsTest extends TestCase
{
    /** @test */
    public function can_view_collection_observation_details()
    {
        $collectionObservation = $this->createCollectionObservation();

        $this->seed('RolesTableSeeder');

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $response = $this->getJson("/api/collection-observations/{$collectionObservation->id}");

        $response->assertSuccessful();
        $this->customAssertArraySubset([
            'id' => $collectionObservation->id,
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification' => 'Testudo hermanii',
            'original_identification_validity' => ObservationIdentificationValidity::VALID,
            'taxon_id' => $collectionObservation->observation->taxon_id,
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'collection_id' => $collectionObservation->collection_id,
            'latitude' => 21.123123,
            'longitude' => 43.123123,
            'location' => 'Gledić Mountains',
            'accuracy' => 10,
            'elevation' => 370,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
        ], $response->json('data'));
    }

    /** @test */
    public function can_list_collection_observations()
    {
        $this->createCollectionObservation();

        $this->seed('RolesTableSeeder');

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $response = $this->getJson('/api/collection-observations');

        $response->assertSuccessful();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(1, $response->json('meta.total'));
    }

    protected function createCollectionObservation()
    {
        $collectionObservation = CollectionObservation::factory()->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => ObservationIdentificationValidity::VALID,
            'collection_id' => SpecimenCollection::factory()->create()->id,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
        ]);

        $collectionObservation->observation()->save(Observation::factory()->make([
            'original_identification' => 'Testudo hermanii',
            'taxon_id' => Taxon::factory()->create()->id,
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'latitude' => 21.123123,
            'longitude' => 43.123123,
            'location' => 'Gledić Mountains',
            'accuracy' => 10,
            'elevation' => 370,
            'created_by_id' => User::factory()->create()->id,
        ]));

        return $collectionObservation;
    }
}
