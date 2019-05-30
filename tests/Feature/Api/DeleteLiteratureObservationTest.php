<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use App\Observation;
use App\LiteratureObservation;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteLiteratureObservationTest extends TestCase
{
    use RefreshDatabase;

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
