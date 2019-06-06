<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use Illuminate\Support\Carbon;
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
        $this->assertCount(2, $response->json('data'));
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
        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function returns_timestamp_of_last_update_to_taxa_tree()
    {
        $taxa = factory(Taxon::class, 5)->create();

        $now = Carbon::now();
        Carbon::setTestNow($now);
        $taxa->first()->touch();

        Passport::actingAs(factory(User::class)->make());

        $response = $this->getJson('/api/taxa');

        $response->assertStatus(200);
        $response->assertJson([
            'meta' => ['last_updated_at' => $now->timestamp],
        ]);
    }

    /** @test */
    public function can_get_only_taxa_updated_after_given_timestamp()
    {
        Carbon::setTestNow($yesterday = Carbon::yesterday());
        $taxa = factory(Taxon::class, 5)->create();

        Carbon::setTestNow();
        $taxa->first()->touch();

        Passport::actingAs(factory(User::class)->make());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'updated_after' => Carbon::now()->timestamp,
        ]));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $taxa->first()->id],
            ],
        ]);
    }

    /** @test */
    public function filtering_by_rank()
    {
        $genus = factory(Taxon::class)->create(['name' => 'Cerambyx', 'rank' => 'genus']);
        $species = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'rank' => 'species', 'parent_id' => $genus->id]);

        Passport::actingAs(factory(User::class)->make());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'rank' => 'genus',
            'name' => 'Cerambyx',
        ]));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $genus->id],
            ],
        ]);
    }
}
