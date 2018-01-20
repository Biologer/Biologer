<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePreferencesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_change_license_preferences()
    {
        $user = factory(User::class)->create([
            'settings' => [
                'data_license' => 10,
                'image_license' => 10,
            ],
        ]);
        $this->actingAs($user);
        $oldSettings = $user->settings()->all();

        $response = $this->from('/contributor/preferences')->patch('/contributor/preferences', [
            'data_license' => 20,
            'image_license' => 30,
        ]);

        $response->assertRedirect('/contributor/preferences');

        $newSettings = $user->fresh()->settings()->all();
        $this->assertNotEquals($oldSettings, $newSettings);
        $this->assertArraySubset([
            'data_license' => 20,
            'image_license' => 30,
        ], $newSettings);
    }

    /** @test */
    public function can_see_preferences_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/contributor/preferences');

        $response->assertViewIs('contributor.preferences.index');
        $response->assertViewHas('preferences', function ($preferences) use ($user) {
            return $preferences->all() === $user->settings()->all();
        });
    }
}
