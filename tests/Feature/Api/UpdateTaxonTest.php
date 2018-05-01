<?php

namespace Tests\Feature\Api;

use App\User;
use App\Stage;
use App\Taxon;
use App\RedList;
use Tests\TestCase;
use App\ConservationDocument;
use Laravel\Passport\Passport;
use App\ConservationLegislation;
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
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);

        $response = $this->putJson("/api/taxa/{$taxon->id}", $this->validParams([
            'name' => 'Cerambyx scopolii',
        ]));

        $response->assertUnauthorized();

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

        $response->assertForbidden();

        $this->assertEquals('Cerambyx cerdo', $taxon->fresh()->name);
    }

    /** @test */
    public function user_with_the_role_of_admin_can_update_taxon()
    {
        $this->withoutExceptionHandling();
        $taxon = factory(Taxon::class)->create([
            'name' => 'Cerambyx cerdo',
            'restricted' => false,
            'allochthonous' => false,
            'invasive' => false,
        ]);
        Passport::actingAs(factory(User::class)->create()->assignRoles('admin'));
        $stages = factory(Stage::class, 2)->create();
        $conservationLegislations = factory(ConservationLegislation::class, 2)->create();
        $conservationDocuments = factory(ConservationDocument::class, 2)->create();
        $redLists = factory(RedList::class, 2)->create();

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
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create()->assignRoles('curator');
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
        $user = factory(User::class)->create()->assignRoles('curator');
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
        $taxon = factory(Taxon::class)->create([
            'parent_id' => factory(Taxon::class)->create(['name' => 'Cerambyx'])->id,
            'name' => 'Cerambyx scopolii',
        ]);
        $taxon->stages()->sync($stages = Stage::all());

        $activityCount = $taxon->activity()->count();

        $user = factory(User::class)->create();
        $taxon->curators()->attach($user);
        Passport::actingAs($user);

        $response = $this->putJson(
            "/api/taxa/{$taxon->id}",
            $this->validParams([
                'parent_id' => factory(Taxon::class)->create()->id,
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
            $this->assertArraySubset([
                'parent' => 'Cerambyx',
                'name' => 'Cerambyx scopolii',
                'stages' => null,
            ], $activity->changes()->get('old'));
            $this->assertEquals('Just testin\' :)', $activity->getExtraProperty('reason'));
        });
    }
}
