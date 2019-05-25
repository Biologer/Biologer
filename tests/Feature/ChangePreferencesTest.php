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

        $response = $this->from('/preferences/license')->patch('/preferences/license', [
            'data_license' => 20,
            'image_license' => 30,
        ]);

        $response->assertRedirect('/preferences/license');

        $newSettings = $user->fresh()->settings()->all();
        $this->assertNotEquals($oldSettings, $newSettings);
        $this->assertArraySubset([
            'data_license' => 20,
            'image_license' => 30,
        ], $newSettings);
    }

    /** @test */
    public function can_see_license_preferences_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/preferences/license');

        $response->assertViewIs('preferences.license');
        $response->assertViewHas('dataLicense', function ($dataLicense) use ($user) {
            return $dataLicense === $user->settings()->data_license;
        });
        $response->assertViewHas('imageLicense', function ($imageLicense) use ($user) {
            return $imageLicense === $user->settings()->image_license;
        });
    }
}
