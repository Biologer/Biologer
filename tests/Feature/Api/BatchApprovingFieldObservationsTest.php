<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use App\FieldObservation;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchApprovingFieldObservationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    public function authenticated_user_that_curates_the_taxa_of_all_the_unapproved_field_observation_can_approve_them()
    {
        $user = factory(User::class)->create()->assignRole('curator');
        Passport::actingAs($user);
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservations = ObservationFactory::createManyUnnapprovedFieldObservations(3, [
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $ids = $fieldObservations->pluck('id')->all();
        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => $ids,
        ]);

        $response->assertSuccessful();

        $fresh = FieldObservation::whereIn('id', $ids)->get();
        $fresh->assertCount(3);
        $fresh->each(function ($fieldObservation) {
            $this->assertTrue($fieldObservation->isApproved());
            $this->assertFalse($fieldObservation->unidentifiable);
        });
    }

    /** @test */
    public function if_one_field_observation_is_approvable_it_will_be_approved()
    {
        $user = factory(User::class)->create()->assignRole('curator');
        Passport::actingAs($user);
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservations = ObservationFactory::createManyUnnapprovedFieldObservations(2, [
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $unapprovable = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => null,
            'created_by_id' => $user->id,
        ]);
        $fieldObservations->push($unapprovable);

        $ids = $fieldObservations->pluck('id')->all();
        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => $ids,
        ]);

        $response->assertSuccessful();

        $fresh = FieldObservation::whereIn('id', $ids)->get();
        $fresh->assertCount(3);
        $fresh->filter->isApproved()->assertCount(2);
    }

    /** @test */
    public function even_one_invalid_field_observation_returns_validation_error()
    {
        $user = factory(User::class)->create()->assignRole('curator');
        Passport::actingAs($user);
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertJsonValidationErrors('field_observation_ids');
    }

    /** @test */
    public function even_one_field_observation_that_user_is_not_authorized_to_approve_results_in_forbidden_error()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $taxon = factory(Taxon::class)->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertUnauthorized();
    }

    /** @test */
    public function guest_cannot_approve_field_observation()
    {
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => factory(Taxon::class),
        ]);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertUnauthenticated();
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    public function cannot_approve_unapproved_field_observation_that_does_not_have_taxon_selected()
    {
        $user = factory(User::class)->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => null,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertValidationErrors('field_observation_ids');
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    public function cannot_approve_unapproved_field_observation_if_taxon_is_not_species_or_lower()
    {
        $user = factory(User::class)->create();
        $taxon = factory(Taxon::class)->create(['rank_level' => 20]);
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertValidationErrors('field_observation_ids');
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    public function cannot_approve_already_approved_field_observation()
    {
        $user = factory(User::class)->create();
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertValidationErrors('field_observation_ids');
    }
}
