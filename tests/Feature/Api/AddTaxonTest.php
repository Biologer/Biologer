<?php

namespace Tests\Feature\Api;

use App\User;
use App\Stage;
use App\Taxon;
use App\RedList;
use Tests\TestCase;
use App\ConservationList;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddTaxonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    protected function validParams(array $overrides = [])
    {
        return array_merge([
            'parent_id' => null,
            'name' => 'Cerambyx cerdo',
            'rank_level' => 10,
            'fe_id' => 'random-string',
            'fe_old_id' => '12345',
            'restricted' => false,
            'stages_ids' => [],
            'conservation_lists_ids' => [],
            'red_lists_ids' => [],
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_create_taxon()
    {
        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertUnauthenticated();

        $this->assertNull(Taxon::findByName('Cerambyx cerdo'));
    }

    /** @test */
    public function unauthorized_user_cannot_create_taxon()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertUnauthorized();

        $this->assertNull(Taxon::findByName('Cerambyx cerdo'));
    }

    /** @test */
    public function user_with_the_role_of_admin_can_create_taxon()
    {
        Passport::actingAs(factory(User::class)->create()->assignRole('admin'));
        $stages = factory(Stage::class, 2)->create();
        $conservationLists = factory(ConservationList::class, 2)->create();
        $redLists = factory(RedList::class, 2)->create();

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
            'stages_ids' => $stages->pluck('id')->all(),
            'conservation_lists_ids' => $conservationLists->pluck('id')->all(),
            'red_lists_data' => $redLists->map(function ($redList) {
                return ['red_list_id' => $redList->id, 'category' => 'EN'];
            })->all(),
        ]));

        $response->assertStatus(201);

        $this->assertNotNull($taxon = Taxon::findByName('Cerambyx cerdo'));
        $this->assertEquals(10, $taxon->rank_level);
        $this->assertEquals('12345', $taxon->fe_old_id);
        $this->assertEquals('random-string', $taxon->fe_id);
        $this->assertFalse($taxon->restricted);
        $taxon->stages->assertEquals($stages);
        $taxon->conservationLists->assertEquals($conservationLists);
        $taxon->redLists->assertEquals($redLists);
    }

    /** @test */
    public function curator_can_create_taxon_that_is_child_of_taxon_they_curate()
    {
        $user = factory(User::class)->create()->assignRole('curator');
        $parentTaxon = factory(Taxon::class)->create();
        $parentTaxon->curators()->attach($user);
        Passport::actingAs($user);

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertStatus(201);
    }

    /** @test */
    public function curator_cannot_create_taxon_if_parent_taxon_is_not_curated_by_them()
    {
        $user = factory(User::class)->create()->assignRole('curator');
        $parentTaxon = factory(Taxon::class)->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertUnauthorized();
    }
}
