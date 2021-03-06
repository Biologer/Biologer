<?php

namespace Tests\Feature\Api;

use App\ConservationDocument;
use App\ConservationLegislation;
use App\RedList;
use App\Stage;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddTaxonTest extends TestCase
{
    protected function setUp(): void
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
            'conservation_legislations_ids' => [],
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
    public function guest_cannot_create_taxon()
    {
        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertUnauthorized();

        $this->assertNull(Taxon::findByName('Cerambyx cerdo'));
    }

    /** @test */
    public function unauthorized_user_cannot_create_taxon()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertForbidden();

        $this->assertNull(Taxon::findByName('Cerambyx cerdo'));
    }

    /** @test */
    public function user_with_the_role_of_admin_can_create_taxon()
    {
        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));
        $parentTaxon = factory(Taxon::class)->create(['rank' => 'genus', 'name' => 'Cerambyx']);
        $stages = factory(Stage::class, 2)->create();
        $conservationLegislations = factory(ConservationLegislation::class, 2)->create();
        $conservationDocuments = factory(ConservationDocument::class, 2)->create();
        $redLists = factory(RedList::class, 2)->create();

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
            'restricted' => true,
            'allochthonous' => true,
            'invasive' => true,
            'stages_ids' => $stages->pluck('id')->all(),
            'conservation_legislations_ids' => $conservationLegislations->pluck('id')->all(),
            'conservation_documents_ids' => $conservationDocuments->pluck('id')->all(),
            'red_lists_data' => $redLists->map(function ($redList) {
                return ['red_list_id' => $redList->id, 'category' => 'EN'];
            })->all(),
        ]));

        $response->assertCreated();

        $taxon = Taxon::findByName('Cerambyx cerdo');

        $this->assertNotNull($taxon);
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
        $taxon->conservationLegislations->assertEquals($conservationLegislations);
        $taxon->conservationDocuments->assertEquals($conservationDocuments);
        $taxon->redLists->assertEquals($redLists);
        $this->assertEquals('Cerambyx', $taxon->ancestors_names);
    }

    /** @test */
    public function curator_can_create_taxon_that_is_child_of_taxon_they_curate()
    {
        $user = factory(User::class)->create()->assignRoles('curator');
        $parentTaxon = factory(Taxon::class)->create();
        $parentTaxon->curators()->attach($user);
        Passport::actingAs($user);

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertCreated();
    }

    /** @test */
    public function curator_cannot_create_taxon_if_parent_taxon_is_not_curated_by_them()
    {
        $user = factory(User::class)->create()->assignRoles('curator');
        $parentTaxon = factory(Taxon::class)->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertForbidden();
    }

    /** @test */
    public function name_must_be_unique_among_roots()
    {
        factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Animalia',
            'parent_id' => null,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function trimmed_version_of_value_is_check()
    {
        factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Animalia ',
            'parent_id' => null,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function checking_unique_name_is_case_insensitive()
    {
        factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'AnimaliA',
            'parent_id' => null,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function name_must_be_unique_within_a_tree()
    {
        $root = factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);

        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $root->id,
            'rank' => 'species',
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function same_name_can_be_used_in_different_trees()
    {
        $root = factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);

        $otherRoot = factory(Taxon::class)->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);
        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $otherRoot->id,
            'rank' => 'species',
        ]));

        $response->assertCreated();
    }
}
