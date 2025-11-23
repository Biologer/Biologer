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
            'synonyms' => [],
            'removed_synonyms' => [],
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
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
        ]));

        $response->assertForbidden();

        $this->assertNull(Taxon::findByName('Cerambyx cerdo'));
    }

    /** @test */
    public function user_with_the_role_of_admin_can_create_taxon()
    {
        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $parentTaxon = Taxon::factory()->create(['rank' => 'genus', 'name' => 'Cerambyx']);
        $stages = Stage::factory(2)->create();
        $conservationLegislations = ConservationLegislation::factory(2)->create();
        $conservationDocuments = ConservationDocument::factory(2)->create();
        $redLists = RedList::factory(2)->create();

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
            'synonyms' => [],
            'removed_synonyms' => [],
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
        $user = User::factory()->create()->assignRoles('curator');
        $parentTaxon = Taxon::factory()->create();
        $parentTaxon->curators()->attach($user);
        Passport::actingAs($user);

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertCreated();
    }

    /** @test */
    public function curator_cannot_create_taxon_if_parent_taxon_is_not_curated_by_them()
    {
        $user = User::factory()->create()->assignRoles('curator');
        $parentTaxon = Taxon::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/taxa', $this->validParams([
            'parent_id' => $parentTaxon->id,
            'name' => 'Cerambyx cerdo',
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertForbidden();
    }

    /** @test */
    public function name_must_be_unique_among_roots()
    {
        Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Animalia',
            'parent_id' => null,
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function trimmed_version_of_value_is_check()
    {
        Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Animalia ',
            'parent_id' => null,
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function checking_unique_name_is_case_insensitive()
    {
        Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'AnimaliA',
            'parent_id' => null,
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function name_must_be_unique_within_a_tree()
    {
        $root = Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $root->id,
            'rank' => 'species',
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function same_name_can_be_used_in_different_trees()
    {
        $root = Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);

        $otherRoot = Taxon::factory()->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);
        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->postJson('/api/taxa', $this->validParams([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $otherRoot->id,
            'rank' => 'species',
            'synonyms' => [],
            'removed_synonyms' => [],
        ]));

        $response->assertCreated();
    }
}
