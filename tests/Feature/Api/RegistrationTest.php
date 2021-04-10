<?php

namespace Tests\Feature\Api;

use App\License;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $passwordClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordClient = app(ClientRepository::class)->createPasswordGrantClient(
            null,
            'Test client',
            url('/'),
        );
    }

    /** @test */
    public function can_register_user_through_api()
    {
        $payload = [
            'client_id' => $this->passwordClient->id,
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ];

        ksort($payload);

        $response = $this->postJson('/api/register', array_merge($payload, [
            'signature' => hash_hmac('sha256', json_encode($payload), $this->passwordClient->secret),
        ]));

        $this->assertNotNull($user = User::firstWhere(['email' => 'test@example.com']));

        $profileResponse = $this->getJson('/api/my/profile', [
            'Authorization' => "Bearer {$response->json('access_token')}",
        ]);

        $this->assertEquals('test@example.com', $profileResponse->json('data.email'));
        $this->assertEquals('Tester', $profileResponse->json('data.first_name'));
        $this->assertEquals('Testerson', $profileResponse->json('data.last_name'));
        $this->assertEquals($user->id, $profileResponse->json('data.id'));
    }

    /** @test */
    public function needs_valid_signature()
    {
        $payload = [
            'client_id' => $this->passwordClient->id,
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ];

        ksort($payload);

        $this->postJson('/api/register', array_merge($payload, [
            'signature' => 'invalid',
        ]))->assertJsonValidationErrors('signature');

        $this->assertFalse(User::where('email', 'test@example.com')->exists());
    }

    /** @test */
    public function signature_is_not_checked_if_client_is_not_valid()
    {
        $payload = [
            'client_id' => 'invalid',
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ];

        ksort($payload);

        $this->postJson('/api/register', array_merge($payload, [
            'signature' => 'invalid',
        ]))
            ->assertJsonValidationErrors('client_id')
            ->assertJsonMissingValidationErrors('signature');

        $this->assertFalse(User::where('email', 'test@example.com')->exists());
    }

    /** @test */
    public function cannot_use_client_that_is_not_password_client()
    {
        $client = app(ClientRepository::class)->create(
            null,
            'Test client',
            url('/'),
        );

        $payload = [
            'client_id' => $client->id,
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ];

        ksort($payload);

        $this->postJson('/api/register', array_merge($payload, [
            'signature' => hash_hmac('sha256', json_encode($payload), $client->secret),
        ]))->assertJsonValidationErrors('client_id');

        $this->assertFalse(User::where('email', 'test@example.com')->exists());
    }
}
