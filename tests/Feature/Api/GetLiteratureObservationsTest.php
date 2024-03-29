<?php

namespace Tests\Feature\Api;

use App\LiteratureObservation;
use App\LiteratureObservationIdentificationValidity;
use App\Observation;
use App\Publication;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetLiteratureObservationsTest extends TestCase
{
    /** @test */
    public function can_view_literature_observation_details()
    {
        $literatureObservation = $this->createLiteratureObservation();

        $this->seed('RolesTableSeeder');

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $response = $this->getJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertSuccessful();
        $this->customAssertArraySubset([
            'id' => $literatureObservation->id,
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification' => 'Testudo hermanii',
            'original_identification_validity' => LiteratureObservationIdentificationValidity::VALID,
            'taxon_id' => $literatureObservation->observation->taxon_id,
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'publication_id' => $literatureObservation->publication_id,
            'is_original_data' => true,
            'cited_publication_id' => null,
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
    public function can_list_literature_observations()
    {
        $literatureObservation = $this->createLiteratureObservation();

        $this->seed('RolesTableSeeder');

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $response = $this->getJson('/api/literature-observations');

        $response->assertSuccessful();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(1, $response->json('meta.total'));
    }

    protected function createLiteratureObservation()
    {
        $literatureObservation = LiteratureObservation::factory()->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => LiteratureObservationIdentificationValidity::VALID,
            'publication_id' => Publication::factory()->create()->id,
            'is_original_data' => true,
            'cited_publication_id' => null,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
        ]);

        $literatureObservation->observation()->save(Observation::factory()->make([
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

        return $literatureObservation;
    }
}
