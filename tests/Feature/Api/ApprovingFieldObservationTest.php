<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\FieldObservation;
use Tests\ObservationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovingFieldObservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authenticated_user_can_approve_unapproved_field_observation_that_has_taxon_selected()
    {
        $user = factory(User::class)->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => factory(Taxon::class),
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

        $response->assertStatus(401);
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

        $response->assertValidationError('field_observation_id');
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

        $response->assertValidationError('field_observation_id');
    }
}
