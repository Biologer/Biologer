<?php

namespace Tests\Feature\Api;

use App\LiteratureObservation;
use App\LiteratureObservationIdentificationValidity;
use App\Publication;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddLiteratureObservationTest extends TestCase
{
    private function validParams($overrides = [])
    {
        return array_map('value', array_merge([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification' => 'Testudo hermanii',
            'original_identification_validity' => LiteratureObservationIdentificationValidity::VALID,
            'taxon_id' => function () {
                return factory(Taxon::class)->create()->id;
            },
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'publication_id' => function () {
                return factory(Publication::class)->create()->id;
            },
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
        ], $overrides));
    }

    /** @test */
    public function quest_cannot_submit_literature_observations()
    {
        $response = $this->postJson('/api/literature-observations', $this->validParams());

        $response->assertUnauthorized();
    }

    /** @test */
    public function regular_authenticated_users_cannot_submit_literature_observations()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observations', $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_submit_literature_observations()
    {
        $this->seed('RolesTableSeeder');
        $count = LiteratureObservation::count();
        $taxon = factory(Taxon::class)->create();
        $user = factory(User::class)->create()->assignRoles('admin');

        Passport::actingAs($user);
        $response = $this->postJson('/api/literature-observations', $this->validParams([
            'taxon_id' => $taxon->id,
            'publication_id' => $publicationId = factory(Publication::class)->create()->id,
        ]));

        $response->assertCreated();
        LiteratureObservation::assertCount($count + 1);
        $literatureObservation = LiteratureObservation::latest()->first();
        $this->assertTrue($literatureObservation->observation->taxon->is($taxon));
        $this->assertEquals('May 12 1990', $literatureObservation->original_date);
        $this->assertEquals('Gledić Mountains', $literatureObservation->original_locality);
        $this->assertEquals('300-500m', $literatureObservation->original_elevation);
        $this->assertEquals('20°22\'44",43°21\'35"', $literatureObservation->original_coordinates);
        $this->assertEquals('Testudo hermanii', $literatureObservation->observation->original_identification);
        $this->assertEquals(LiteratureObservationIdentificationValidity::VALID, $literatureObservation->original_identification_validity);
        $this->assertEquals($taxon->id, $literatureObservation->observation->taxon_id);
        $this->assertEquals(1990, $literatureObservation->observation->year);
        $this->assertEquals(5, $literatureObservation->observation->month);
        $this->assertEquals(12, $literatureObservation->observation->day);
        $this->assertEquals($publicationId, $literatureObservation->publication_id);
        $this->assertTrue($literatureObservation->is_original_data);
        $this->assertNull($literatureObservation->cited_publication_id);
        $this->assertEquals(21.123123, $literatureObservation->observation->latitude);
        $this->assertEquals(43.123123, $literatureObservation->observation->longitude);
        $this->assertEquals('Gledić Mountains', $literatureObservation->observation->location);
        $this->assertEquals(10, $literatureObservation->observation->accuracy);
        $this->assertEquals(370, $literatureObservation->observation->elevation);
        $this->assertEquals(350, $literatureObservation->minimum_elevation);
        $this->assertEquals(400, $literatureObservation->maximum_elevation);
        $this->assertEquals(400, $literatureObservation->maximum_elevation);
        $this->assertEquals('Pera Detlić', $literatureObservation->georeferenced_by);
        $this->assertEquals(now()->toDateString(), $literatureObservation->georeferenced_date->toDateString());
    }
}
