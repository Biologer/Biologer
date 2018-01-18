<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use App\FieldObservation;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovingFieldObservationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    function authenticated_user_that_curates_the_taxon_can_approve_unapproved_field_observation_that_has_taxon_selected()
    {
        $user = factory(User::class)->create()->assignRole('curator');
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);
        $response = $this->postJson('/api/approved-field-observations', [
            'field_observation_id' => $fieldObservation->id,
        ]);

        $response->assertSuccessful();
        $this->assertTrue($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    function guest_cannot_approve_field_observation()
    {
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => factory(Taxon::class),
        ]);

        $response = $this->postJson('/api/approved-field-observations', [
            'field_observation_id' => $fieldObservation->id,
        ]);

        $response->assertUnauthenticated();
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    function cannot_approve_unapproved_field_observation_that_does_not_have_taxon_selected()
    {
        $user = factory(User::class)->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => null,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations', [
            'field_observation_id' => $fieldObservation->id,
        ]);

        $response->assertValidationErrors('field_observation_id');
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    function cannot_approve_unapproved_field_observation_if_taxon_is_not_species_or_lower()
    {
        $user = factory(User::class)->create();
        $taxon = factory(Taxon::class)->create(['rank_level' => 20]);
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations', [
            'field_observation_id' => $fieldObservation->id,
        ]);

        $response->assertValidationErrors('field_observation_id');
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    function cannot_approve_already_approved_field_observation()
    {
        $user = factory(User::class)->create();
        $fieldObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => null,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations', [
            'field_observation_id' => $fieldObservation->id,
        ]);

        $response->assertValidationErrors('field_observation_id');
    }
}
