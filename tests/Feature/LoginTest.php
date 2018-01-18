<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Nikazooz\LaravelCaptcha\Facades\Captcha;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_with_verified_their_email_can_login()
    {
        $user = factory(User::class)->create([
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
    function user_that_has_not_verified_their_email_is_redirected_to_verify_page()
    {
        factory(User::class)->states('unverified')->create([
            'email' => 'test@example.com',
            'password' => bcrypt('top-secret-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'top-secret-password',
        ]);

        $response->assertRedirect('login');

        $this->assertTrue(auth()->guest());
    }

    /** @test */
    function cannot_login_with_invalid_email()
    {
        factory(User::class)->states('unverified')->create([
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
    function cannot_login_with_invalid_password()
    {
        factory(User::class)->states('unverified')->create([
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
