<?php

namespace Tests\Feature\Api;

use App\LiteratureObservation;
use App\Observation;
use App\ObservationIdentificationValidity;
use App\Publication;
use App\Stage;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateLiteratureObservationTest extends TestCase
{
    /** @test */
    public function guest_cannot_update_observation()
    {
        $literatureObservation = $this->createLiteratureObservation();

        $response = $this->putJson("/api/literature-observations/{$literatureObservation->id}", $this->validParams());

        $response->assertUnauthorized();
    }

    /** @test */
    public function unauthorized_user_cannot_update_observation()
    {
        $literatureObservation = $this->createLiteratureObservation();

        Passport::actingAs(User::factory()->create());
        $response = $this->putJson("/api/literature-observations/{$literatureObservation->id}", $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_update_literature_observations()
    {
        $this->seed('RolesTableSeeder');
        $literatureObservation = $this->createLiteratureObservation();
        $taxon = Taxon::factory()->create();
        $stage = Stage::factory()->create();
        $publication = Publication::factory()->create();
        $citedPublication = Publication::factory()->create();

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $response = $this->putJson("/api/literature-observations/{$literatureObservation->id}", $this->validParams([
            'taxon_id' => $taxon->id,
            'stage_id' => $stage->id,
            'publication_id' => $publication->id,
            'cited_publication_id' => $citedPublication->id,
        ]));

        $response->assertSuccessful();
        $literatureObservation = $literatureObservation->refresh();
        $this->assertEquals('May 13 1991', $literatureObservation->original_date);
        $this->assertEquals('Balkan Mountain', $literatureObservation->original_locality);
        $this->assertEquals('32-50m', $literatureObservation->original_elevation);
        $this->assertEquals('21°22\'44",41°21\'35"', $literatureObservation->original_coordinates);
        $this->assertEquals('Testuduo hermanii', $literatureObservation->observation->original_identification);
        $this->assertEquals(ObservationIdentificationValidity::INVALID, $literatureObservation->original_identification_validity);
        $this->assertTrue($literatureObservation->observation->taxon->is($taxon));
        $this->assertEquals(1991, $literatureObservation->observation->year);
        $this->assertEquals(5, $literatureObservation->observation->month);
        $this->assertEquals(13, $literatureObservation->observation->day);
        $this->assertTrue($literatureObservation->publication->is($publication));
        $this->assertFalse($literatureObservation->is_original_data);
        $this->assertTrue($literatureObservation->citedPublication->is($citedPublication));
        $this->assertEquals(22.123123, $literatureObservation->observation->latitude);
        $this->assertEquals(44.123123, $literatureObservation->observation->longitude);
        $this->assertEquals('Balkan Mountain', $literatureObservation->observation->location);
        $this->assertEquals(20, $literatureObservation->observation->accuracy);
        $this->assertEquals(37, $literatureObservation->observation->elevation);
        $this->assertEquals(32, $literatureObservation->minimum_elevation);
        $this->assertEquals(50, $literatureObservation->maximum_elevation);
        $this->assertEquals('Scooby Doo', $literatureObservation->georeferenced_by);
        $this->assertEquals(now()->subDay()->toDateString(), $literatureObservation->georeferenced_date->toDateString());
        $this->assertEquals('female', $literatureObservation->observation->sex);
        $this->assertTrue($literatureObservation->observation->stage->is($stage));
        $this->assertEquals('Some other information', $literatureObservation->other_original_data);
        $this->assertEquals(1990, $literatureObservation->collecting_start_year);
        $this->assertEquals(3, $literatureObservation->collecting_start_month);
        $this->assertEquals(1990, $literatureObservation->collecting_end_year);
        $this->assertEquals(6, $literatureObservation->collecting_end_month);
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
                return Taxon::factory()->create()->id;
            },
            'year' => 1991,
            'month' => 5,
            'day' => 13,
            'publication_id' => function () {
                return Publication::factory()->create()->id;
            },
            'is_original_data' => false,
            'cited_publication_id' => function () {
                return Publication::factory()->create()->id;
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
                return Stage::factory()->create()->id;
            },
        ], $overrides));
    }

    protected function createLiteratureObservation()
    {
        $literatureObservation = LiteratureObservation::factory()->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => ObservationIdentificationValidity::VALID,
            'other_original_data' => 'Some information',
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
            'sex' => 'male',
            'stage_id' => Stage::factory()->create(['name' => 'Old stage'])->id,
        ]));

        return $literatureObservation;
    }
}
