<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use App\FieldObservation;
use Tests\TestCase;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchApprovingFieldObservationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
    }

    /** @test */
    function authenticated_user_that_curates_the_taxa_of_all_the_unapproved_field_observation_can_approve_them()
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

        $fresh = FieldObservation::whereIn('id', $ids);
        $this->assertEquals(3, $fresh->count());
        $fresh->each(function ($fieldObservation) {
            $this->assertTrue($fieldObservation->isApproved());
            $this->assertFalse($fieldObservation->unidentifiable);
        });
    }

    /** @test */
    function even_one_invalid_field_observation_returns_validation_error()
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

        $response->assertJsonValidationErrors('field_observation_ids.0');
    }

    /** @test */
    function even_one_field_observation_that_user_is_not_authorized_to_approve_results_in_forbidden_error()
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

        $response->assertStatus(403);
    }
}
