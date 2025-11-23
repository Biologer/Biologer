<?php

namespace Tests\Feature\Api;

use App\User;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetUserInformationTest extends TestCase
{
    #[Test]
    public function users_cen_retreive_treir_information(): void
    {
        $user = User::factory()->create([
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
