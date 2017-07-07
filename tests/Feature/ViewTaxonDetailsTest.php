<?php

namespace Tests\Feature;

use App\Taxon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewTaxonDetailsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function can_see_basic_taxon_information()
    {
        $taxon = factory(Taxon::class)->create([
            'name' => 'Cerambyx cerdo',
        ]);

        $response = $this->get("/taxa/{$taxon->id}")
            ->assertStatus(200)
            ->assertSee('Cerambyx cerdo');
        $this->assertTrue($response->data('taxon')->is($taxon));
    }
}
