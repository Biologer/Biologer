<?php

namespace Tests\Feature\Api;

use App\Notifications\FieldObservationApproved;
use App\Models\User;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class MarkNotificationsAsReadTest extends TestCase
{
    #[Test]
    public function guests_cannot_mark_notifications_as_read()
    {
        $user = User::factory()->create();
        $fieldObservation = ObservationFactory::createFieldObservation();

        $user->notify(new FieldObservationApproved($fieldObservation, $user));
        $user->unreadNotifications()->assertCount(1);
        $user->readNotifications()->assertCount(0);

        $this->postJson('/api/my/read-notifications/batch', [
            'notifications_ids' => [$user->unreadNotifications->first()->id],
        ])->assertUnauthorized();

        $user->unreadNotifications()->assertCount(1);
        $user->readNotifications()->assertCount(0);
    }

    #[Test]
    public function authenticated_user_can_mark_their_notifications_as_read()
    {
        $user = User::factory()->create();
        $fieldObservation = ObservationFactory::createFieldObservation();

        $user->notify(new FieldObservationApproved($fieldObservation, $user));
        $user->unreadNotifications()->assertCount(1);
        $user->readNotifications()->assertCount(0);

        Passport::actingAs($user);
        $this->postJson('/api/my/read-notifications/batch', [
            'notifications_ids' => [$user->unreadNotifications->first()->id],
        ])->assertSuccessful();

        $user->unreadNotifications()->assertCount(0);
        $user->readNotifications()->assertCount(1);
    }
}
