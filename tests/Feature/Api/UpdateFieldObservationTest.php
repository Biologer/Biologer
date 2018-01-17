<?php

namespace Tests\Feature\Api;

use App\User;
use App\Photo;
use App\Taxon;
use Carbon\Carbon;
use Tests\TestCase;
use App\Observation;
use App\FieldObservation;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
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
            'year' => 2017,
            'month' => 7,
            'day' => 15,
            'location' => 'Novi Sad',
            'latitude' => 45.251667,
            'longitude' => 19.836944,
            'accuracy' => 100,
            'elevation' => 80,
            'source' => 'John Doe',
            'taxon_suggestion' => null,
        ], $overrides);
    }

    /** @test */
    function field_observation_can_be_updated_by_user_who_created_it_if_its_not_approved()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);
        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}", $this->validParams([
                'elevation' => 1000,
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'elevation' => 1000,
                'taxon_suggestion' => 'New taxon suggestion',
            ]
        ]);

        tap($fieldObservation->fresh(), function ($fieldObservation) {
            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }

    /** @test */
    function field_observation_cannot_be_updated_by_user_who_created_it_if_approved()
    {
        $user = factory(User::class)->create();
        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);
        $response = $this->putJson(
            "/api/field-observations/{$observation->id}", $this->validParams([
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(403);

        tap($observation->fresh(), function ($fieldObservation) {
            $this->assertNotEquals(1000, $fieldObservation->observation->elevation);
            $this->assertNotEquals('New observer', $fieldObservation->observation->observer);
            $this->assertNotEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }

    /** @test */
    function field_observation_can_be_updated_by_admin()
    {
        $user = factory(User::class)->create()->assignRole('admin');

        $fieldObservation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);
        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}", $this->validParams([
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
            ]
        ]);

        tap($fieldObservation->fresh(), function ($fieldObservation) {
            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('New observer', $fieldObservation->observation->observer);
            $this->assertEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }
}
