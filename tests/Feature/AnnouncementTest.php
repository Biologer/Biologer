<?php

namespace Tests\Feature;

use App\Announcement;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    private function validParams($overrides = [])
    {
        return array_merge([
            'title' => [
                'en' => 'Test title',
            ],
            'message' => [
                'en' => 'Test message',
            ],
            'private' => false,
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_publish_announcements()
    {
        $this->postJson('/api/announcements', $this->validParams())->assertUnauthorized();
    }

    /** @test */
    public function admin_can_publish_an_announcement()
    {
        $this->seed('RolesTableSeeder');
        $user = User::factory()->create()->assignRoles('admin');
        Passport::actingAs($user);

        $response = $this->postJson('/api/announcements', $this->validParams());

        $response->assertCreated();
        $announcement = Announcement::latest()->first();

        $this->assertEquals('Test title', $announcement->title);
        $this->assertEquals('Test message', $announcement->message);
        $this->assertFalse($announcement->private);
    }

    /** @test */
    public function guests_can_view_public_announcements()
    {
        $announcement = Announcement::factory()->create(['private' => false]);

        $response = $this->get("/announcements/{$announcement->id}");

        $response->assertViewHas('announcement', function ($viewAnnouncement) use ($announcement) {
            return $viewAnnouncement->is($announcement);
        });
        $response->assertSee($announcement->title);
    }

    /** @test */
    public function guests_cannot_view_private_announcements()
    {
        $announcement = Announcement::factory()->create(['private' => true]);

        $this->get("/announcements/{$announcement->id}")->assertNotFound();
    }

    /** @test */
    public function authenticated_users_can_mark_announcements_as_read()
    {
        $this->seed('RolesTableSeeder');
        $announcement = Announcement::factory()->create(['private' => false]);
        Passport::actingAs(User::factory()->create());

        $this->assertFalse($announcement->isRead());

        $response = $this->postJson('/api/read-announcements', [
            'announcement_id' => $announcement->id,
        ])->assertSuccessful();

        $this->assertTrue($announcement->fresh()->isRead());
    }

    /** @test */
    public function announcement_is_marked_as_read_when_authenticated_user_views_it()
    {
        $this->seed('RolesTableSeeder');
        $announcement = Announcement::factory()->create(['private' => false]);
        $this->actingAs(User::factory()->create());

        $this->assertFalse($announcement->isRead());

        $response = $this->get("/announcements/{$announcement->id}");

        $this->assertTrue($announcement->fresh()->isRead());
    }
}
