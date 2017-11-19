<?php

namespace Tests\Feature\Api;

use App\User;
use App\Photo;
use App\Taxon;
use Carbon\Carbon;
use Tests\TestCase;
use App\Observation;
use App\FieldObservation;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ObservationFactory;

class UpdateFieldObservationTest extends TestCase
{
    use RefreshDatabase;

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
    function field_observation_can_be_updated()
    {
        $user = factory(User::class)->create();
        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);
        $response = $this->withoutExceptionHandling()->json(
            'PUT', "/api/field-observations/{$observation->id}", $this->validParams([
                'elevation' => 1000,
                'source' => 'New source',
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'elevation' => 1000,
            'source' => 'New source',
            'taxon_suggestion' => 'New taxon suggestion',
        ]);

        tap($observation->fresh(), function ($fieldObservation) {
            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('New source', $fieldObservation->source);
            $this->assertEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }
}
