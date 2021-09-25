<?php

namespace Tests\Feature\Api;

use App\CollectionObservation;
use App\Observation;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DeleteCollectionObservationTest extends TestCase
{
    /** @test */
    public function guest_cannot_delete_observation()
    {
        $collectionObservation = CollectionObservation::factory()->create();
        $count = CollectionObservation::count();

        $response = $this->deleteJson("/api/collection-observations/{$collectionObservation->id}");

        $response->assertUnauthorized();
        CollectionObservation::assertCount($count);
    }

    /** @test */
    public function unauthorized_user_cannot_delete_observation()
    {
        $collectionObservation = CollectionObservation::factory()->create();
        $count = CollectionObservation::count();

        Passport::actingAs(User::factory()->create());
        $response = $this->deleteJson("/api/collection-observations/{$collectionObservation->id}");

        $response->assertForbidden();
        CollectionObservation::assertCount($count);
    }

    /** @test */
    public function curator_or_admin_can_delete_collection_observations()
    {
        $this->seed('RolesTableSeeder');
        $collectionObservation = CollectionObservation::factory()->create();
        $collectionObservation->observation()->save(Observation::factory()->make());
        $count = CollectionObservation::count();

        Passport::actingAs(User::factory()->create()->assignRoles('curator'));
        $response = $this->deleteJson("/api/collection-observations/{$collectionObservation->id}");

        $response->assertStatus(204);
        $this->assertNull($collectionObservation->fresh());
        CollectionObservation::assertCount($count - 1);
    }
}
