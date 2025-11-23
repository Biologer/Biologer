<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\User;
use Tests\TestCase;

final class ChangePreferencesTest extends TestCase
{
    #[Test]
    public function can_change_license_preferences(): void
    {
        $user = User::factory()->create([
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
        $this->customAssertArraySubset([
            'data_license' => 20,
            'image_license' => 30,
        ], $newSettings);
    }

    #[Test]
    public function can_see_license_preferences_page(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

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
