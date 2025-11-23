<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Taxon;
use Tests\TestCase;

class ViewTaxonDetailsTest extends TestCase
{
    #[Test]
    public function can_see_basic_taxon_information(): void
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
