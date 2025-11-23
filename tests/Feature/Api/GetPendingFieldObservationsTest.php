<?php

namespace Tests\Feature\Api;

use App\Taxon;
use App\User;
use Database\Seeders\RolesTableSeeder;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class GetPendingFieldObservationsTest extends TestCase
{
    #[Test]
    public function curator_can_get_pending_field_observations_of_taxa_they_curate()
    {
        $this->seed(RolesTableSeeder::class);
        $animalia = Taxon::factory()->create(['name' => 'Animalia', 'rank' => 'kingdom']);
        $coleoptera = Taxon::factory()->parent($animalia)->create(['name' => 'Coleoptera', 'rank' => 'order']);

        $observation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $coleoptera->id,
            'created_by_id' => User::factory()->create()->id,
        ]);

        $curator = User::factory()->curator($animalia)->create();

        Passport::actingAs($curator);

        $response = $this->get('/api/curator/pending-observations');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $observation->id, 'taxon_id' => $coleoptera->id],
            ],
        ]);
    }

    #[Test]
    public function curator_can_get_pending_field_observations_of_ancestors_of_taxa_they_curate()
    {
        $this->seed(RolesTableSeeder::class);
        $animalia = Taxon::factory()->create(['name' => 'Animalia', 'rank' => 'kingdom']);
        $coleoptera = Taxon::factory()->parent($animalia)->create(['name' => 'Coleoptera', 'rank' => 'order']);

        $observation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $animalia->id,
            'created_by_id' => User::factory()->create()->id,
        ]);

        $curator = User::factory()->curator($coleoptera)->create();

        Passport::actingAs($curator);

        $response = $this->get('/api/curator/pending-observations');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $observation->id, 'taxon_id' => $animalia->id],
            ],
        ]);
    }

    #[Test]
    public function curator_can_get_pending_field_observations_of_taxa_they_dont_curate()
    {
        $this->seed(RolesTableSeeder::class);
        $animalia = Taxon::factory()->create(['name' => 'Animalia', 'rank' => 'kingdom']);
        $coleoptera = Taxon::factory()->parent($animalia)->create(['name' => 'Coleoptera', 'rank' => 'order']);
        $lepidoptera = Taxon::factory()->parent($animalia)->create(['name' => 'Lepidoptera', 'rank' => 'order']);

        $observation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $lepidoptera->id,
            'created_by_id' => User::factory()->create()->id,
        ]);

        $curator = User::factory()->curator($coleoptera)->create();

        Passport::actingAs($curator);

        $response = $this->get('/api/curator/pending-observations');

        $response->assertOk();
        $response->assertJsonMissing([
            'data' => [
                ['id' => $observation->id, 'taxon_id' => $lepidoptera->id],
            ],
        ]);
    }
}
