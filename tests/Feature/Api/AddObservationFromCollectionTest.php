<?php

namespace Tests\Feature\Api;

use App\CollectionObservation;
use App\Taxon;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class AddObservationFromCollectionTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_map('value', array_merge([
            'taxon_id' => function () {
                return factory(Taxon::class)->create()->id;
            },

        ], $overrides));
    }

    /** @test */
    public function guests_cannot_add_observation_from_collection()
    {
        $this->postJson('/api/collection-observations', $this->validParams())
            ->assertUnauthorized();
    }

    /** @test */
    public function curator_can_add_observations_from_collections()
    {
        $this->seed('RolesTableSeeder');
        Passport::actingAs(factory(User::class)->create()->assignRoles('curator'));

        $taxon = factory(Taxon::class)->create();

        $this->postJson('/api/collection-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]))->assertSuccessful();

        $collectionObservation = CollectionObservation::latest()->first();

        $this->assertTrue($collectionObservation->observation->taxon->is($taxon));
    }

    /** @test */
    public function taxon_is_required_when_adding_observation_from_collection()
    {

    }

}
