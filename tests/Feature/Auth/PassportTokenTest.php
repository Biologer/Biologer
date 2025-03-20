<?php

namespace Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Tests\TestCase;

class PassportTokenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /** @test */
    public function guest_cannot_access_token_routes()
    {
        $this->getJson(route('preferences.token'))->assertStatus(401);
        $this->postJson(route('preferences.token.generate'), ['name' => 'New test access token'])->assertStatus(401);
        $this->postJson(route('preferences.token.revoke'), ['token_id' => 'randomTokenId'])->assertStatus(401);
    }

    /** @test */
    public function unverified_user_cannot_generate_or_revoke_token()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        Passport::actingAs($user);

        $this->postJson(route('preferences.token.generate'), ['name' => 'New test access token'])->assertForbidden();
        $this->postJson(route('preferences.token.revoke'), ['token_id' => 'randomTokenId'])->assertForbidden();
    }

    /** @test */
    public function verified_user_can_generate_access_token()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Passport::actingAs($user);

        $response = $this->postJson(route('preferences.token.generate'), ['name' => 'New test access token']);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'id']);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'user_id' => $user->id,
            'revoked' => false,
        ]);
    }

    /** @test */
    public function verified_user_cannot_generate_token_if_already_exists()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Passport::actingAs($user);

        $user->createToken('Existing test access token');

        $response = $this->postJson(route('preferences.token.generate'), ['name' => 'Existing test access token']);

        $response->assertStatus(400)
            ->assertJson(['message' => 'You already have a valid token.']);
    }

    /** @test */
    public function verified_user_can_revoke_tokens()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Passport::actingAs($user);

        $token = $user->createToken('Existing test access token');

        $response = $this->postJson(route('preferences.token.revoke'), ['token_id' => $token->token->id]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Token revoked successfully']);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id' => $token->token->id,
            'revoked' => true,
        ]);
    }

    /** @test */
    public function verified_user_cannot_revoke_non_existent_token()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Passport::actingAs($user);

        $response = $this->postJson(route('preferences.token.revoke'), ['token_id' => 'nonexistentTokenId']);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Token not found or already revoked']);
    }

    /** @test */
    /** @test */
    public function verified_user_cannot_revoke_already_revoked_token()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Passport::actingAs($user);

        $token = $user->createToken('Existing test access token');

        $accessToken = Token::where('id', $token->token->id)->first();

        $accessToken->revoke();

        $response = $this->postJson(route('preferences.token.revoke'), ['token_id' => $accessToken->id]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Token not found or already revoked']);
    }

}
