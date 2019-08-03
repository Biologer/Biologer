<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\PendingNotification;
use Tests\ObservationFactory;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FieldObservationApproved;
use App\Notifications\UnreadNotificationsSummary;

class SendingUnreadNotificationSummaryEmailTest extends TestCase
{
    /** @test */
    public function users_with_unread_mail_notifications_are_sent_summary()
    {
        $this->seed('RolesTableSeeder');
        $user = factory(User::class)->create();
        $user->settings()->set('notifications.field_observation_approved.mail', true);
        $curator = factory(User::class)->create()->assignRoles('curator');

        $user->notify(new FieldObservationApproved(
            ObservationFactory::createFieldObservation(),
            $curator
        ));

        PendingNotification::assertCount(1);
        $this->assertFalse(PendingNotification::first()->is_sent);

        Notification::fake();

        PendingNotification::sendOut();

        $this->assertTrue(PendingNotification::first()->is_sent);
        Notification::assertSentTo($user, UnreadNotificationsSummary::class);
    }
}
