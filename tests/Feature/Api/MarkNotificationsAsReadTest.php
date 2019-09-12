<?php

namespace Tests\Feature\Api;

use App\Notifications\FieldObservationApproved;
use App\User;
use Laravel\Passport\Passport;
use Tests\ObservationFactory;
use Tests\TestCase;

class MarkNotificationsAsReadTest extends TestCase
{
    /** @test */
    public function guests_cannot_mark_notifications_as_read()
    {
        $user = factory(User::class)->create();
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

    /** @test */
    public function authenticated_user_can_mark_their_notifications_as_read()
    {
        $user = factory(User::class)->create();
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
