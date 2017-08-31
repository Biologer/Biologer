<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaxaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_fetch_list_of_taxa()
    {
        $taxa = factory(Taxon::class, 5)->create();
        Passport::actingAs(factory(User::class)->make());

        $response = $this->json('GET', '/api/taxa');

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(5, $response->json()['data']);

        $taxa->each(function ($taxon) use ($response) {
            $response->assertJsonFragment([
                'id' => $taxon->id,
                'name' => $taxon->name,
            ]);
        });
    }
}
