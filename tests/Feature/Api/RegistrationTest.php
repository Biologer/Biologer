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
        $response = $this->postJson('/api/register', [
            'client_id' => $this->passwordClient->id,
            'client_secret' => $this->passwordClient->secret,
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ])->assertOk();

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
    public function needs_valid_client_secret()
    {
        $this->postJson('/api/register', [
            'client_id' => $this->passwordClient->id,
            'client_secret' => 'invalid',
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ])->assertJsonValidationErrors('client_secret');

        $this->assertFalse(User::where('email', 'test@example.com')->exists());
    }

    /** @test */
    public function signature_is_not_checked_if_client_is_not_valid()
    {
        $this->postJson('/api/register', [
            'client_id' => 'invalid',
            'client_secret' => 'invalid',
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ])->assertJsonValidationErrors('client_id')->assertJsonMissingValidationErrors('client_secret');

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

        $this->postJson('/api/register', [
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'first_name' => 'Tester',
            'last_name' => 'Testerson',
            'data_license' => License::CC_BY_NC_SA,
            'image_license' => License::CC_BY_NC_SA,
            'email' => 'test@example.com',
            'password' => 'supersecret',
        ])->assertJsonValidationErrors('client_id');

        $this->assertFalse(User::where('email', 'test@example.com')->exists());
    }
}
