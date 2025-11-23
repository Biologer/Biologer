<?php

namespace Tests\Feature;

use App\Taxon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ViewTaxonDetailsTest extends TestCase
{
    #[Test]
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
