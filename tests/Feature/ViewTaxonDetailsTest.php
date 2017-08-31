<?php

namespace Tests\Feature;

use App\Taxon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTaxonDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_see_basic_taxon_information()
    {
        $taxon = factory(Taxon::class)->create([
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
