<?php

namespace Tests\Feature\Api;

use App\Taxon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $taxon = factory(Taxon::class)->create();

        $this->postJson('/api/collection-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]))->assertSuccessful();

        $observation = CollectionObservation::latest()->first();

        $this->assertTrue($observation->taxon->is($taxon));
    }

    /** @test */
    public function taxon_is_required_when_adding_observation_from_collection()
    {

    }

}
