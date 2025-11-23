<?php

namespace Tests\Feature\Api;

use App\Models\Taxon;
use App\Models\User;
use App\Models\ViewGroup;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaxaTest extends TestCase
{
    #[Test]
    public function can_fetch_list_of_taxa()
    {
        $taxa = Taxon::factory(5)->create();
        Passport::actingAs(User::factory()->make());

        $response = $this->getJson('/api/taxa');

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(Taxon::count(), $response->json()['data']);

        $taxa->each(function ($taxon) use ($response) {
            $response->assertJsonFragment([
                'id' => $taxon->id,
                'name' => $taxon->name,
            ]);
        });
    }

    #[Test]
    public function can_fetch_paginated_list_of_taxa()
    {
        Taxon::factory(5)->create();
        Passport::actingAs(User::factory()->make());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'page' => 1,
            'per_page' => 2,
        ]));

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(2, $response->json('data'));
    }

    #[Test]
    public function can_exclude_taxa_with_provided_ids()
    {
        $taxa = Taxon::factory(5)->create();
        Passport::actingAs(User::factory()->make());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'except' => $taxa->take(2)->pluck('id')->all(),
        ]));

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(Taxon::count() - 2, $response->json('data'));
    }

    #[Test]
    public function can_get_only_taxa_updated_after_given_timestamp()
    {
        Carbon::setTestNow($yesterday = Carbon::yesterday());
        $taxa = Taxon::factory(5)->create();

        Carbon::setTestNow();
        $taxa->first()->touch();

        Passport::actingAs(User::factory()->make());

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

    #[Test]
    public function filtering_by_rank()
    {
        $genus = Taxon::factory()->create(['name' => 'Cerambyx', 'rank' => 'genus']);
        $species = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species', 'parent_id' => $genus->id]);

        Passport::actingAs(User::factory()->make());

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

    #[Test]
    public function filtering_by_name_and_id()
    {
        $cerdo = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $scopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

        $cerdo->update([
            'sr' => ['native_name' => 'Test Name', 'description' => 'Test description'],
        ]);

        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'name' => 'Cerambyx cerdo',
            'taxonId' => $cerdo->id,
        ]));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $cerdo->id],
            ],
        ]);
        $response->assertJsonMissing(['id' => $scopolii->id]);
    }

    #[Test]
    public function filtering_by_groups()
    {
        $cerdo = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $scopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

        $viewGroupCerdo = ViewGroup::factory()->create();
        $viewGroupCerdo->taxa()->attach($cerdo);

        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'groups' => [
                $viewGroupCerdo->id,
            ],
        ]));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $cerdo->id],
            ],
        ]);
        $response->assertJsonMissing(['id' => $scopolii->id]);
    }

    #[Test]
    public function filtering_by_not_being_in_any_group()
    {
        $cerdo = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $scopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

        $viewGroupCerdo = ViewGroup::factory()->create();
        $viewGroupCerdo->taxa()->attach($cerdo);

        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'ungrouped' => true,
        ]));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $scopolii->id],
            ],
        ]);
        $response->assertJsonMissing(['id' => $cerdo->id]);
    }

    #[Test]
    public function filtering_both_in_group_and_ungrouped()
    {
        $cerdo = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $scopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

        $viewGroupCerdo = ViewGroup::factory()->create();
        $viewGroupCerdo->taxa()->attach($cerdo);

        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'groups' => [$viewGroupCerdo->id],
            'ungrouped' => true,
        ]));

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $cerdo->id],
                ['id' => $scopolii->id],
            ],
        ]);
    }

    #[Test]
    public function include_groups_ids()
    {
        $cerambyx = Taxon::factory()->create(['name' => 'Cerambyx', 'rank' => 'genus']);
        $cerambyxScopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species', 'parent_id' => $cerambyx->id]);

        $viewGroup = ViewGroup::factory()->create();
        $viewGroup->taxa()->attach($cerambyx);

        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/taxa?'.http_build_query([
            'withGroupsIds' => true,
        ]));

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
        $response->assertJson([
            'data' => [
                ['id' => $cerambyx->id, 'groups' => [$viewGroup->id]],
                ['id' => $cerambyxScopolii->id, 'groups' => [$viewGroup->id]],
            ],
        ]);
    }
}
