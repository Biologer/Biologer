<?php

namespace Tests\Feature\Auth;

use App\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    #[Test]
    public function user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('top-secret-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'top-secret-password',
        ]);

        $response->assertRedirect('/contributor');

        $this->assertTrue(auth()->user()->is($user));
    }

    #[Test]
    public function user_that_has_not_verified_their_email_is_redirected_to_verification_notice_page(): void
    {
        User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('top-secret-password'),
        ]);

        $response = $this->followingRedirects()->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'top-secret-password',
        ]);

        $response->assertOk();
        $response->assertViewIs('auth.verify');
    }

    #[Test]
    public function cannot_login_with_invalid_email(): void
    {
        User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('top-secret-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'invalid-email@example.com',
            'password' => 'top-secret-password',
        ]);

        $response->assertRedirect('login');
        $response->assertSessionHasErrors('email');

        $this->assertTrue(auth()->guest());
    }

    #[Test]
    public function cannot_login_with_invalid_password(): void
    {
        User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('top-secret-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('login');
        $response->assertSessionHasErrors('email');

        $this->assertTrue(auth()->guest());
    }
}
