<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use App\Observation;
use App\Publication;
use App\LiteratureObservation;
use Laravel\Passport\Passport;
use App\LiteratureObservationIdentificationValidity;

class GetLiteratureObservationsTest extends TestCase
{
    /** @test */
    public function can_view_literature_observation_details()
    {
        $literatureObservation = $this->createLiteratureObservation();

        $this->seed('RolesTableSeeder');

        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));
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

        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));
        $response = $this->getJson('/api/literature-observations');

        $response->assertSuccessful();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(1, $response->json('meta.total'));
    }

    protected function createLiteratureObservation()
    {
        $literatureObservation = factory(LiteratureObservation::class)->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => LiteratureObservationIdentificationValidity::VALID,
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
            'cited_publication_id' => null,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
        ]);

        $literatureObservation->observation()->save(factory(Observation::class)->make([
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
        ]));

        return $literatureObservation;
    }
}
