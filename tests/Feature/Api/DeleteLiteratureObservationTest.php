<?php

namespace Tests\Feature\Api;

use App\LiteratureObservation;
use App\Observation;
use App\User;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DeleteLiteratureObservationTest extends TestCase
{
    #[Test]
    public function guest_cannot_delete_observation(): void
    {
        $literatureObservation = LiteratureObservation::factory()->create();
        $count = LiteratureObservation::count();

        $response = $this->deleteJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertUnauthorized();
        LiteratureObservation::assertCount($count);
    }

    #[Test]
    public function unauthorized_user_cannot_delete_observation(): void
    {
        $literatureObservation = LiteratureObservation::factory()->create();
        $count = LiteratureObservation::count();

        Passport::actingAs(User::factory()->create());
        $response = $this->deleteJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertForbidden();
        LiteratureObservation::assertCount($count);
    }

    #[Test]
    public function admin_can_delete_literature_observations(): void
    {
        $this->seed('RolesTableSeeder');
        $literatureObservation = LiteratureObservation::factory()->create();
        $literatureObservation->observation()->save(Observation::factory()->make());
        $count = LiteratureObservation::count();

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $response = $this->deleteJson("/api/literature-observations/{$literatureObservation->id}");

        $response->assertStatus(204);
        $this->assertNull($literatureObservation->fresh());
        LiteratureObservation::assertCount($count - 1);
    }
}
