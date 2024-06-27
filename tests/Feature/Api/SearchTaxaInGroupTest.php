<?php

namespace Tests\Feature\Api;

use App\Taxon;
use App\User;
use App\ViewGroup;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SearchTaxaInGroupTest extends TestCase
{
    /** @test */
    public function can_find_taxa_in_group_by_name_with_id_of_first_species()
    {
        $group = ViewGroup::factory()->create([
            'parent_id' => ViewGroup::factory(),
        ]);

        $order = Taxon::factory()->create(['name' => ' Lepidoptera', 'rank' => 'order']);
        $family = Taxon::factory()->create(['parent_id' => $order->id, 'name' => 'Lycaenidae', 'rank' => 'family']);
        $genus = Taxon::factory()->create(['parent_id' => $family->id, 'name' => 'Polyommatus', 'rank' => 'genus']);
        $species = Taxon::factory()->create(['parent_id' => $genus->id, 'name' => 'Polyommatus eros', 'rank' => 'species']);
        $subspecies = Taxon::factory()->create(['parent_id' => $species->id, 'name' => 'Polyommatus eros eroides', 'rank' => 'subspecies']);

        $group->taxa()->attach($order);

        Passport::actingAs(User::factory()->make());
        $response = $this->get("/api/groups/{$group->id}/taxa?".http_build_query([
            'name' => 'Polyommatus',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                ['id' => $genus->id, 'name' => 'Polyommatus', 'first_species_id' => $genus->id],
                ['id' => $species->id, 'name' => 'Polyommatus eros', 'first_species_id' => $species->id],
                ['id' => $subspecies->id, 'name' => 'Polyommatus eros eroides'],
            ],
        ]);
    }
}
