<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserInformationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_cen_retreive_treir_information()
    {
        $user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'settings' => [
                'language' => 'sr-Latn',
                'data_license' => 10,
                'image_license' => 20,
            ],
        ]);

        Passport::actingAs($user);
        $response = $this->getJson('/api/my/profile');

        $response->assertSuccessful();
        $response->assertJson([
            'data' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
                'settings' => [
                    'language' => 'sr-Latn',
                    'data_license' => 10,
                    'image_license' => 20,
                ],
            ],
        ]);
    }
}
