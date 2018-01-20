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
    public function can_fetch_list_of_taxa()
    {
        $taxa = factory(Taxon::class, 5)->create();
        Passport::actingAs(factory(User::class)->make());

        $response = $this->getJson('/api/taxa');

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

    /** @test */
    public function can_fetch_paginated_list_of_taxa()
    {
        $taxa = factory(Taxon::class, 5)->create();
        Passport::actingAs(factory(User::class)->make());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'page' => 1,
            'per_page' => 2,
        ]));

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(2, $response->json()['data']);
    }

    /** @test */
    public function can_exclude_taxa_with_provided_ids()
    {
        $taxa = factory(Taxon::class, 5)->create();
        Passport::actingAs(factory(User::class)->make());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'except' => $taxa->take(2)->pluck('id')->all(),
        ]));

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(3, $response->json()['data']);
    }
}
