<?php

namespace Tests\Feature;

use App\Taxon;
use App\User;
use Tests\ObservationFactory;
use Tests\TestCase;

class PendingFieldObservationsEditTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    public function guests_cannot_visit_curator_page_to_edit_pending_observation()
    {
        $taxon = Taxon::factory()->create();
        $observation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
        ]);

        $response = $this->get("/curator/pending-observations/{$observation->id}/edit");

        $response->assertRedirect('/login');
    }

    /** @test */
    public function curator_can_open_page_to_edit_pending_field_observation()
    {
        $curator = User::factory()->create()->assignRoles('curator');
        $taxon = Taxon::factory()->create()->addCurator($curator);
        $observation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
        ]);

        $response = $this->actingAs($curator)->get("/curator/pending-observations/{$observation->id}/edit");

        $response->assertOk();
        $response->assertViewIs('curator.pending-observations.edit');
        $response->assertViewHas('fieldObservation', function ($viewObservation) use ($observation) {
            return $observation->is($viewObservation);
        });
    }

    /** @test */
    public function curator_cannot_open_page_to_edit_pending_field_observation_for_taxon_they_dont_curate()
    {
        $curator = User::factory()->create()->assignRoles('curator');
        $taxon = Taxon::factory()->create();
        $observation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
        ]);

        $response = $this->actingAs($curator)->get("/curator/pending-observations/{$observation->id}/edit");

        $response->assertForbidden();
    }
}
