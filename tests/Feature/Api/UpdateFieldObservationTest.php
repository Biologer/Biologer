<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use App\Observation;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFieldObservationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /**
     * Valid observation data.
     *
     * @param  array  $overrides
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'taxon_id' => null,
            'year' => '2017',
            'month' => '7',
            'day' => '15',
            'location' => 'Novi Sad',
            'latitude' => '45.251667',
            'longitude' => '19.836944',
            'accuracy' => '100',
            'elevation' => '80',
            'source' => 'John Doe',
            'taxon_suggestion' => null,
            'time' => '12:30',
            'sex' => 'male',
        ], $overrides);
    }

    /** @test */
    public function field_observation_can_be_updated_by_user_who_created_it_if_its_not_approved()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
        ]);

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'elevation' => 1000,
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'elevation' => 1000,
                'taxon_suggestion' => 'New taxon suggestion',
            ],
        ]);

        tap($fieldObservation->fresh(), function ($fieldObservation) {
            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }

    /** @test */
    public function field_observation_cannot_be_updated_by_user_who_created_it_if_approved()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
        ]);

        $response = $this->putJson(
            "/api/field-observations/{$observation->id}",
            $this->validParams([
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertUnauthorized();

        tap($observation->fresh(), function ($fieldObservation) {
            $this->assertNotEquals(1000, $fieldObservation->observation->elevation);
            $this->assertNotEquals('New observer', $fieldObservation->observation->observer);
            $this->assertNotEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }

    /** @test */
    public function field_observation_can_be_updated_by_admin()
    {
        $user = factory(User::class)->create()->assignRole('admin');
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
        ]);

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ],
        ]);

        tap($fieldObservation->fresh(), function ($fieldObservation) {
            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('New observer', $fieldObservation->observation->observer);
            $this->assertEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }
}
