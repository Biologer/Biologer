<?php

namespace Tests\Feature\Api;

use App\FieldObservation;
use App\Notifications\FieldObservationMovedToPending;
use App\Taxon;
use App\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class MoveFieldObservationsToPendingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');

        Notification::fake();
    }

    #[Test]
    public function guest_cannot_mark_field_observation_as_unidentifiable()
    {
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => Taxon::factory(),
        ]);

        $response = $this->postJson('/api/unidentifiable-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertUnauthorized();
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    #[Test]
    public function authenticated_user_that_curates_the_taxa_of_all_the_field_observation_can_mark_them_as_unapprovable()
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

    #[Test]
    public function creator_is_notified_when_the_observation_is_moved_to_pending()
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
        $this->postJson('/api/pending-field-observations/batch', [
            'field_observation_ids' => $fieldObservations->pluck('id')->all(),
            'reason' => 'Testing',
        ])->assertSuccessful();

        Notification::assertTimesSent(3, FieldObservationMovedToPending::class);

        $fieldObservations->each(function ($observation) use ($user, $curator) {
            Notification::assertSentTo(
                $user,
                FieldObservationMovedToPending::class,
                function ($notification) use ($observation, $curator) {
                    return $notification->curator->is($curator) &&
                        $notification->fieldObservation->is($observation);
                }
            );
        });
    }
}
