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

class UpdateTaxonTest extends TestCase
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
            'rank' => 'species',
            'fe_id' => 'random-string',
            'fe_old_id' => '12345',
            'restricted' => false,
            'allochthonous' => false,
            'invasive' => false,
            'stages_ids' => [],
            'conservation_lists_ids' => [],
            'red_lists_ids' => [],
            'native_name' => [
                app()->getLocale() => 'oak longhorn beetle',
            ],
            'description' => [
                app()->getLocale() => 'test description',
            ],
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_update_taxon()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertUnauthenticated();

        $this->assertEquals('Cerambyx cerdo', $taxon->fresh()->name);
    }

    /** @test */
    public function unauthorized_user_cannot_update_taxon()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        Passport::actingAs(factory(User::class)->create());

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertUnauthorized();

        $this->assertEquals('Cerambyx cerdo', $taxon->fresh()->name);
    }

    /** @test */
    public function user_with_the_role_of_admin_can_update_taxon()
    {
        $taxon = factory(Taxon::class)->create([
            'name' => 'Cerambyx cerdo',
            'restricted' => false,
            'allochthonous' => false,
            'invasive' => false,
        ]);
        Passport::actingAs(factory(User::class)->create()->assignRole('admin'));
        $stages = factory(Stage::class, 2)->create();
        $conservationLists = factory(ConservationList::class, 2)->create();
        $redLists = factory(RedList::class, 2)->create();

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
            'restricted' => true,
            'allochthonous' => true,
            'invasive' => true,
            'stages_ids' => $stages->pluck('id')->all(),
            'conservation_lists_ids' => $conservationLists->pluck('id')->all(),
            'red_lists_data' => $redLists->map(function ($redList) {
                return ['red_list_id' => $redList->id, 'category' => 'EN'];
            })->all(),
        ]));

        $response->assertSuccessful();

        $taxon = $taxon->fresh();
        $this->assertEquals('Cerambyx scopolii', $taxon->name);
        $this->assertEquals('species', $taxon->rank);
        $this->assertEquals(Taxon::RANKS['species'], $taxon->rank_level);
        $this->assertEquals('12345', $taxon->fe_old_id);
        $this->assertEquals('random-string', $taxon->fe_id);
        $this->assertTrue($taxon->restricted);
        $this->assertTrue($taxon->allochthonous);
        $this->assertTrue($taxon->invasive);
        $this->assertEquals($taxon->native_name, 'oak longhorn beetle');
        $this->assertEquals($taxon->description, 'test description');
        $taxon->stages->assertEquals($stages);
        $taxon->conservationLists->assertEquals($conservationLists);
        $taxon->redLists->assertEquals($redLists);
    }

    /** @test */
    public function curator_can_update_taxon_that_they_curate()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create()->assignRole('curator');
        $taxon->curators()->attach($user);
        Passport::actingAs($user);

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertSuccessful();

        $this->assertEquals('Cerambyx scopolii', $taxon->fresh()->name);
    }

    /** @test */
    public function curator_cannot_update_taxon_that_is_not_curated_by_them()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create()->assignRole('curator');
        Passport::actingAs($user);

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertUnauthorized();
    }
}
