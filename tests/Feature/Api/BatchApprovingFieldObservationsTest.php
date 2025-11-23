<?php

namespace Tests\Feature\Api;

use App\FieldObservation;
use App\Notifications\FieldObservationApproved;
use App\Taxon;
use App\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class BatchApprovingFieldObservationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');

        Notification::fake();
    }

    #[Test]
    public function authenticated_user_that_curates_the_taxa_of_all_the_unapproved_field_observation_can_approve_them(): void
    {
        $user = User::factory()->create()->assignRoles('curator');
        Passport::actingAs($user);
        $taxon = Taxon::factory()->create();
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

    #[Test]
    public function if_one_field_observation_is_approvable_it_will_be_approved(): void
    {
        $user = User::factory()->create()->assignRoles('curator');
        Passport::actingAs($user);
        $taxon = Taxon::factory()->create();
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

    #[Test]
    public function even_one_invalid_field_observation_returns_validation_error(): void
    {
        $user = User::factory()->create()->assignRoles('curator');
        Passport::actingAs($user);
        $taxon = Taxon::factory()->create();
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

    #[Test]
    public function even_one_field_observation_that_user_is_not_authorized_to_approve_results_in_forbidden_error(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $taxon = Taxon::factory()->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertForbidden();
    }

    #[Test]
    public function guest_cannot_approve_field_observation(): void
    {
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => Taxon::factory(),
        ]);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertUnauthorized();
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    #[Test]
    public function cannot_approve_unapproved_field_observation_that_does_not_have_taxon_selected(): void
    {
        $user = User::factory()->create();
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => null,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertJsonValidationErrors('field_observation_ids');
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    #[Test]
    public function cannot_approve_unapproved_field_observation_if_taxon_is_not_species_or_lower(): void
    {
        $user = User::factory()->create();
        $taxon = Taxon::factory()->create(['rank_level' => 20]);
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertJsonValidationErrors('field_observation_ids');
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    #[Test]
    public function cannot_approve_already_approved_field_observation(): void
    {
        $user = User::factory()->create();
        $taxon = Taxon::factory()->create();
        $taxon->curators()->attach($user);
        $fieldObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertJsonValidationErrors('field_observation_ids');
    }

    #[Test]
    public function creator_is_notified_when_the_observation_is_approved(): void
    {
        $user = User::factory()->create();
        $curator = User::factory()->create()->assignRoles('curator');

        $taxon = Taxon::factory()->create();
        $taxon->curators()->attach($curator);
        $fieldObservations = ObservationFactory::createManyUnnapprovedFieldObservations(3, [
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($curator);
        $this->postJson('/api/approved-field-observations/batch', [
            'field_observation_ids' => $fieldObservations->pluck('id')->all(),
        ])->assertSuccessful();

        Notification::assertTimesSent(3, FieldObservationApproved::class);

        $fieldObservations->each(function ($observation) use ($user, $curator) {
            Notification::assertSentTo(
                $user,
                FieldObservationApproved::class,
                function ($notification) use ($observation, $curator) {
                    return $notification->curator->is($curator) &&
                        $notification->fieldObservation->is($observation);
                }
            );
        });
    }
}
