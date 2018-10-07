<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Announcement;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

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
        $user = factory(User::class)->create()->assignRoles('admin');
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
        $announcement = factory(Announcement::class)->create(['private' => false]);

        $response = $this->get("/announcements/{$announcement->id}");

        $response->assertViewHas('announcement', function ($viewAnnouncement) use ($announcement) {
            return $viewAnnouncement->is($announcement);
        });
        $response->assertSee($announcement->title);
    }

    /** @test */
    public function guests_cannot_view_private_announcements()
    {
        $announcement = factory(Announcement::class)->create(['private' => true]);

        $this->get("/announcements/{$announcement->id}")->assertNotFound();
    }

    /** @test */
    public function authenticated_users_can_mark_announcements_as_read()
    {
        $announcement = factory(Announcement::class)->create(['private' => false]);
        Passport::actingAs(factory(User::class)->create());

        $this->assertFalse($announcement->isRead());

        $response = $this->postJson("/api/read-announcements", [
            'announcement_id' => $announcement->id,
        ])->assertSuccessful();

        $this->assertTrue($announcement->fresh()->isRead());
    }

    /** @test */
    public function announcement_is_marked_as_read_when_authenticated_user_views_it()
    {
        $announcement = factory(Announcement::class)->create(['private' => false]);
        $this->actingAs(factory(User::class)->create());

        $this->assertFalse($announcement->isRead());

        $response = $this->get("/announcements/{$announcement->id}");

        $this->assertTrue($announcement->fresh()->isRead());
    }
}
