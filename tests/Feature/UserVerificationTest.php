<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Nikazooz\LaravelCaptcha\Facades\Captcha;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_verify_unverified_user()
    {
        $user = factory(User::class)->states('unverified')->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->get("/verify/token/{$user->verificationToken->token}");

        $this->assertTrue($user->fresh()->verified);
        $response->assertRedirect('/login');
    }

    /** @test */
    function already_verified_user_cannot_verify_again()
    {
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
            'verified' => true,
        ]);

        $response = $this->get("/verify/token/{$user->verificationToken->token}");

        $response->assertStatus(404);
    }

    /** @test */
    function can_resend_verification_email_to_unverified_user()
    {
        Mail::fake();

        $user = factory(User::class)->states('unverified')->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->post('/verify/resend', [
            'email' => 'john@example.com',
            'captcha_code' => Captcha::getVerificationCode(),
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('info');

        Mail::assertQueued(VerificationEmail::class, function ($mail) use ($user) {
            return $mail->hasTo('john@example.com')
                && $mail->verificationToken->is($user->verificationToken);
        });
    }

    /** @test */
    function will_not_send_verification_to_verfied_user()
    {
        Mail::fake();

        factory(User::class)->create([
            'email' => 'john@example.com',
            'verified' => true,
        ]);

        $response = $this->from('/verify/john@example.com')->post('/verify/resend', [
            'email' => 'john@example.com',
            'captcha_code' => Captcha::getVerificationCode(),
        ]);

        $response->assertRedirect('/verify/john@example.com');

        Mail::assertNotQueued(VerificationEmail::class);
        Mail::assertNotSent(VerificationEmail::class);
    }

    /** @test */
    function will_not_send_if_the_user_has_not_registered()
    {
        Mail::fake();

        $this->assertFalse(User::where('email', 'john@example.com')->exists());

        $response = $this->from('/verify/john@example.com')->post('/verify/resend', [
            'email' => 'john@example.com',
            'captcha_code' => Captcha::getVerificationCode(),
        ]);

        $response->assertRedirect('/verify/john@example.com');

        Mail::assertNotQueued(VerificationEmail::class);
        Mail::assertNotSent(VerificationEmail::class);
    }

    /** @test */
    function can_see_verify_page()
    {
        $user = factory(User::class)->states('unverified')->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->get('/verify/test@example.com');

        $response->assertSuccessful();
        $response->assertViewIs('auth.verify');
        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->is($user);
        });
    }

    /** @test */
    function verify_page_is_unavailable_if_the_user_does_not_exist()
    {
        $response = $this->get('/verify/test@example.com');

        $response->assertStatus(404);
    }

    /** @test */
    function verify_page_is_unavailable_if_the_user_is_already_verified()
    {
        factory(User::class)->create([
            'email' => 'test@example.com',
            'verified' => true
        ]);

        $response = $this->get('/verify/test@example.com');

        $response->assertStatus(404);
    }
}
