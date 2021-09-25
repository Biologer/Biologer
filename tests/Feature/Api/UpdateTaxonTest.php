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

class UpdateTaxonTest extends TestCase
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
            'conservation_documents_ids' => [],
            'red_lists_ids' => [],
            'native_name' => [
                app()->getLocale() => 'oak longhorn beetle',
            ],
            'description' => [
                app()->getLocale() => 'test description',
            ],
            'reason' => 'Testing',
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_update_taxon()
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertUnauthorized();

        $this->assertEquals('Cerambyx cerdo', $taxon->fresh()->name);
    }

    /** @test */
    public function unauthorized_user_cannot_update_taxon()
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        Passport::actingAs(User::factory()->create());

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertForbidden();

        $this->assertEquals('Cerambyx cerdo', $taxon->fresh()->name);
    }

    /** @test */
    public function user_with_the_role_of_admin_can_update_taxon()
    {
        $taxon = Taxon::factory()->create([
            'name' => 'Cerambyx cerdo',
            'restricted' => false,
            'allochthonous' => false,
            'invasive' => false,
        ]);
        Passport::actingAs(User::factory()->create()->assignRoles('admin'));
        $stages = Stage::factory(2)->create();
        $conservationLegislations = ConservationLegislation::factory(2)->create();
        $conservationDocuments = ConservationDocument::factory(2)->create();
        $redLists = RedList::factory(2)->create();

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
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
        $taxon->conservationLegislations->assertEquals($conservationLegislations);
        $taxon->conservationDocuments->assertEquals($conservationDocuments);
        $taxon->redLists->assertEquals($redLists);
    }

    /** @test */
    public function curator_can_update_taxon_that_they_curate()
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create()->assignRoles('curator');
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
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create()->assignRoles('curator');
        Passport::actingAs($user);

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertForbidden();
    }

    /** @test */
    public function activity_log_entry_is_added_when_field_observation_is_updated()
    {
        $this->artisan('db:seed', ['--class' => 'StagesTableSeeder']);
        $taxon = Taxon::factory()->create([
            'parent_id' => Taxon::factory()->create(['name' => 'Cerambyx'])->id,
            'name' => 'Cerambyx scopolii',
        ]);
        $taxon->stages()->sync($stages = Stage::all());

        $activityCount = $taxon->activity()->count();

        $user = User::factory()->create();
        $taxon->curators()->attach($user);
        Passport::actingAs($user);

        $response = $this->putJson(
            "/api/taxa/{$taxon->id}",
            $this->validParams([
                'parent_id' => Taxon::factory()->create()->id,
                'name' => 'Cerambyx cerdo',
                'restricted' => true,
                'allochthonous' => true,
                'invasive' => true,
                'stages_ids' => [],
                'reason' => 'Just testin\' :)',
            ])
        );

        $response->assertStatus(200);

        tap($taxon->fresh(), function ($taxon) use ($activityCount, $user, $stages) {
            $taxon->activity->assertCount($activityCount + 1);
            $activity = $taxon->activity->latest()->first();

            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->causer->is($user));
            $this->customAssertArraySubset([
                'parent' => 'Cerambyx',
                'name' => 'Cerambyx scopolii',
                'stages' => null,
            ], $activity->changes()->get('old'));
            $this->assertEquals('Just testin\' :)', $activity->getExtraProperty('reason'));
        });
    }

    /** @test */
    public function name_must_be_unique_among_roots()
    {
        Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        $taxon = Taxon::factory()->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Animalia',
            'parent_id' => $taxon->parent_id,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    public function trimmed_version_of_value_is_check()
    {
        Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        $taxon = Taxon::factory()->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Animalia ',
            'parent_id' => $taxon->parent_id,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function checking_unique_name_is_case_insensitive()
    {
        Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        $taxon = Taxon::factory()->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'AnimaliA',
            'parent_id' => $taxon->parent_id,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function name_must_be_unique_within_a_tree()
    {
        $root = Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'parent_id' => $root->id, 'rank' => 'species']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $taxon->parent_id,
            'rank' => $taxon->rank,
        ]));

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function same_name_can_be_used_in_different_trees()
    {
        $root = Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);

        $otherRoot = Taxon::factory()->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx scopilii', 'parent_id' => $otherRoot->id, 'rank' => 'species']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $taxon->parent_id,
            'rank' => $taxon->rank,
        ]));

        $response->assertSuccessful();
    }

    /** @test */
    public function unique_name_validation_is_ignored_if_using_the_same_name()
    {
        $taxon = Taxon::factory()->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => $taxon->name,
            'parent_id' => $taxon->parent_id,
            'rank' => $taxon->rank,
        ]));

        $response->assertSuccessful();
    }

    /** @test */
    public function changing_parent_rebuilds_ancestry_for_descendants()
    {
        $oldParent = Taxon::factory()->create(['rank' => 'order', 'name' => 'Wrong']);
        $newParent = Taxon::factory()->create(['rank' => 'order', 'name' => 'Coleoptera']);
        $taxon = Taxon::factory()->create(['rank' => 'family', 'parent_id' => $oldParent->id, 'name' => 'Cerambycidae']);
        $child = Taxon::factory()->create(['rank' => 'genus', 'parent_id' => $taxon->id, 'name' => 'Cerambyx']);
        $grandChild = Taxon::factory()->create(['rank' => 'species', 'parent_id' => $child->id, 'name' => 'Cerambyx cerdo']);

        // Sanity check
        $child->ancestors->assertContains($oldParent);
        $child->ancestors->assertContains($taxon);
        $grandChild->ancestors->assertContains($oldParent);
        $grandChild->ancestors->assertContains($taxon);
        $grandChild->ancestors->assertContains($child);
        $this->assertEquals('Wrong,Cerambycidae', $child->fresh()->ancestors_names);
        $this->assertEquals('Wrong,Cerambycidae,Cerambyx', $grandChild->fresh()->ancestors_names);

        $taxon->update(['parent_id' => $newParent->id]);
        $child->refresh();
        $grandChild->refresh();

        $child->ancestors->assertContains($newParent);
        $child->ancestors->assertContains($taxon);
        $grandChild->ancestors->assertContains($newParent);
        $grandChild->ancestors->assertContains($taxon);
        $grandChild->ancestors->assertContains($child);
        $this->assertEquals('Coleoptera,Cerambycidae', $child->ancestors_names);
        $this->assertEquals('Coleoptera,Cerambycidae,Cerambyx', $grandChild->ancestors_names);
    }
}
