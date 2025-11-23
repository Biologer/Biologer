<?php

namespace Tests\Feature;

use App\Notifications\FieldObservationApproved;
use App\Notifications\UnreadNotificationsSummary;
use App\PendingNotification;
use App\User;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class SendingUnreadNotificationSummaryEmailTest extends TestCase
{
    #[Test]
    public function users_with_unread_mail_notifications_are_sent_summary(): void
    {
        $this->seed('RolesTableSeeder');
        $user = User::factory()->create();
        $user->settings()->set('notifications.field_observation_approved.mail', true);
        $curator = User::factory()->create()->assignRoles('curator');

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
