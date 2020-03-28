<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddObservationFromCollectionTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'taxon_id' => 'Cerambyx cerdo',
        ], $overrides);
    }

    /** @test */
    public function guests_cannot_add_observation_from_collection()
    {
        $this->postJson('/api/collection-observations', $this->validParams())
            ->assertUnauthorized();
    }
}
