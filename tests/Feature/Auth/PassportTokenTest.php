<?php

namespace Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PassportTokenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function guest_cannot_access_token_routes()
    {
        $this->getJson(route('preferences.token'))->assertStatus(401);
        $this->postJson(route('preferences.token.generate'))->assertStatus(401);
        $this->postJson(route('preferences.token.revoke'))->assertStatus(401);
    }

    /** @test */
    public function unverified_user_cannot_generate_or_revoke_token()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        Passport::actingAs($user);

        $this->postJson(route('preferences.token.generate'))->assertForbidden();
        $this->postJson(route('preferences.token.revoke'))->assertForbidden();
    }

    /** @test */
    public function verified_user_can_generate_access_token()
    {
        $user = User::factory()->create(['email_verified_at' => now()])->fresh();
        Passport::actingAs($user);

        $response = $this->postJson(route('preferences.token.generate'), ['name' => 'Test Token']);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'id']);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'user_id' => $user->id,
            'revoked' => false,
        ]);
    }

    /** @test */
    public function verified_user_can_revoke_tokens()
    {
        $user = User::factory()->create(['email_verified_at' => now()])->fresh();
        Passport::actingAs($user);

        // Generate a token to revoke
        $token = $user->createToken('Test Token');

        $this->assertDatabaseHas('oauth_access_tokens', [
            'user_id' => $user->id,
            'revoked' => false,
        ]);

        $response = $this->postJson(route('preferences.token.revoke'), ['token_id' => $token->accessToken]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Token revoked successfully']);

        $this->assertDatabaseMissing('oauth_access_tokens', [
            'user_id' => $user->id,
            'revoked' => false,
        ]);
    }
}
