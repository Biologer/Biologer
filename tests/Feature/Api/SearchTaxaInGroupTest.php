<?php

namespace Tests\Feature\Api;

use App\Taxon;
use App\User;
use App\ViewGroup;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchTaxaInGroupTest extends TestCase
{
    #[Test]
    public function can_find_taxa_in_group_by_name_with_id_of_first_species(): void
    {
        $group = ViewGroup::factory()->create([
            'parent_id' => ViewGroup::factory(),
        ]);

        $order = Taxon::factory()->create(['name' => 'Coleoptera', 'rank' => 'order']);
        $family = Taxon::factory()->create(['parent_id' => $order->id, 'name' => 'Cerambycidae', 'rank' => 'family']);
        $genus = Taxon::factory()->create(['parent_id' => $family->id, 'name' => 'Cerambyx', 'rank' => 'genus']);
        $species = Taxon::factory()->create(['parent_id' => $genus->id, 'name' => 'Cerambyx cerdo', 'rank' => 'species']);

        $group->taxa()->attach($order);

        Passport::actingAs(User::factory()->make());
        $response = $this->get("/api/groups/{$group->id}/taxa?".http_build_query([
            'name' => 'Cerambyx',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                ['id' => $genus->id, 'name' => 'Cerambyx', 'first_species_id' => $species->id],
                ['id' => $species->id, 'name' => 'Cerambyx cerdo', 'first_species_id' => $species->id],
            ],
        ]);
    }
}
