<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function user_can_login()
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

    /** @test */
    public function user_that_has_not_verified_their_email_is_redirected_to_verification_notice_page()
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

    /** @test */
    public function cannot_login_with_invalid_email()
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

    /** @test */
    public function cannot_login_with_invalid_password()
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
