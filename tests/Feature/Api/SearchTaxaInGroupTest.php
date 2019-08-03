<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use App\ViewGroup;
use Tests\TestCase;
use Laravel\Passport\Passport;

class SearchTaxaInGroupTest extends TestCase
{
    /** @test */
    public function can_find_taxa_in_group_by_name_with_id_of_first_species()
    {
        $group = factory(ViewGroup::class)->create([
            'parent_id' => factory(ViewGroup::class),
        ]);

        $order = factory(Taxon::class)->create(['name' => 'Coleoptera', 'rank' => 'order']);
        $family = factory(Taxon::class)->create(['parent_id' => $order->id, 'name' => 'Cerambycidae', 'rank' => 'family']);
        $genus = factory(Taxon::class)->create(['parent_id' => $family->id, 'name' => 'Cerambyx', 'rank' => 'genus']);
        $species = factory(Taxon::class)->create(['parent_id' => $genus->id, 'name' => 'Cerambyx cerdo', 'rank' => 'species']);

        $group->taxa()->attach($order);

        Passport::actingAs(factory(User::class)->make());
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
