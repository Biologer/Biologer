<?php

namespace Tests\Feature\Api;

use App\FieldObservation;
use App\Notifications\FieldObservationMarkedUnidentifiable;
use App\Taxon;
use App\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\ObservationFactory;
use Tests\TestCase;

class BatchMarkingFieldObservationsAsUnidentifiableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');

        Notification::fake();
    }

    /** @test */
    public function guest_cannot_mark_field_observation_as_unidentifiable()
    {
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => factory(Taxon::class),
        ]);

        $response = $this->postJson('/api/unidentifiable-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertUnauthorized();
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    public function authenticated_user_that_curates_the_taxa_of_all_the_field_observation_can_mark_them_as_unapprovable()
    {
        $user = factory(User::class)->create()->assignRoles('curator');
        Passport::actingAs($user);
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservations = ObservationFactory::createManyUnnapprovedFieldObservations(3, [
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $ids = $fieldObservations->pluck('id')->all();
        $response = $this->postJson('/api/unidentifiable-field-observations/batch', [
            'field_observation_ids' => $ids,
            'reason' => 'Testing',
        ]);

        $response->assertSuccessful();

        $fresh = FieldObservation::whereIn('id', $ids)->get();
        $fresh->assertCount(3);
        $fresh->each(function ($fieldObservation) {
            $this->assertTrue($fieldObservation->unidentifiable);
            $this->assertFalse($fieldObservation->isApproved());
        });
    }

    /** @test */
    public function creator_is_notified_when_the_observation_is_marked_as_unidentifiable()
    {
        $user = factory(User::class)->create();
        $curator = factory(User::class)->create()->assignRoles('curator');

        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($curator);
        $fieldObservations = ObservationFactory::createManyUnnapprovedFieldObservations(3, [
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        Passport::actingAs($curator);
        $this->postJson('/api/unidentifiable-field-observations/batch', [
            'field_observation_ids' => $fieldObservations->pluck('id')->all(),
            'reason' => 'Testing',
        ])->assertSuccessful();

        Notification::assertTimesSent(3, FieldObservationMarkedUnidentifiable::class);

        $fieldObservations->each(function ($observation) use ($user, $curator) {
            Notification::assertSentTo(
                $user,
                FieldObservationMarkedUnidentifiable::class,
                function ($notification) use ($observation, $curator) {
                    return $notification->curator->is($curator) &&
                        $notification->fieldObservation->is($observation);
                }
            );
        });
    }
}
