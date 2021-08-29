<?php

namespace Tests\Feature;

use App\Taxon;
use Tests\TestCase;

class ViewTaxonDetailsTest extends TestCase
{
    /** @test */
    public function can_see_basic_taxon_information()
    {
        $taxon = Taxon::factory()->create([
            'name' => 'Cerambyx cerdo',
        ]);

        $this->get("/taxa/{$taxon->id}")
            ->assertStatus(200)
            ->assertSee('Cerambyx cerdo')
            ->assertViewHas('taxon', function ($t) use ($taxon) {
                return $t->is($taxon);
            });
    }
}
