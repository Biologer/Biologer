<?php

namespace Tests\Feature;

use App\Taxon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ViewTaxonDetailsTest extends TestCase
{
    #[Test]
    public function can_see_basic_taxon_information(): void
    {
        $taxon = Taxon::factory()->create([
            'name' => 'Cerambyx cerdo',
        ]);

        $response = $this->get("/taxa/{$taxon->id}");
        $response->assertStatus(200);
        $response->assertSee('Cerambyx cerdo');
    }
}
