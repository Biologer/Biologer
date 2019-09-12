<?php

namespace Tests\Feature\Api;

use App\LiteratureObservation;
use App\Observation;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DeleteLiteratureObservationTest extends TestCase
{
    /** @test */
    public function guest_cannot_delete_observation()
    {
        $literatureObservation = factory(LiteratureObservation::class)->create();
        $count = LiteratureObservation::count();

        $response = $this->deleteJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertUnauthorized();
        LiteratureObservation::assertCount($count);
    }

    /** @test */
    public function unauthorized_user_cannot_delete_observation()
    {
        $literatureObservation = factory(LiteratureObservation::class)->create();
        $count = LiteratureObservation::count();

        Passport::actingAs(factory(User::class)->create());
        $response = $this->deleteJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertForbidden();
        LiteratureObservation::assertCount($count);
    }

    /** @test */
    public function admin_can_delete_literature_observations()
    {
        $this->seed('RolesTableSeeder');
        $literatureObservation = factory(LiteratureObservation::class)->create();
        $literatureObservation->observation()->save(factory(Observation::class)->make());
        $count = LiteratureObservation::count();

        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));
        $response = $this->deleteJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertStatus(204);
        $this->assertNull($literatureObservation->fresh());
        LiteratureObservation::assertCount($count - 1);
    }
}
