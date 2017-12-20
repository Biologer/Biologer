<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\VerificationToken;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Nikazooz\LaravelCaptcha\Facades\Captcha;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function validParams($overrides = [])
    {
        return array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'top-secret-password',
            'password_confirmation' => 'top-secret-password',
            'data_license' => 10,
            'image_license' => 20,
            'captcha_verification_code' => Captcha::getVerificationCode(),
        ], $overrides);
    }

    /** @test */
    function user_can_register_by_providing_required_information()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->post('/register', $this->validParams());

        $response->assertRedirect('/login');
        $response->assertSessionHas('info');
        $this->assertTrue(auth()->guest());

        $this->assertEquals(1, User::count());
        $user = User::first();
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('top-secret-password', $user->password));
        $this->assertFalse($user->verified);
        $this->assertEquals(10, $user->settings()->get('data_license'));
        $this->assertEquals(20, $user->settings()->get('image_license'));

        Mail::assertQueued(VerificationEmail::class, function ($mail) use ($user) {
            return $mail->hasTo('john@example.com')
                && $mail->verificationToken->is($user->verificationToken);
        });
    }

    /** @test */
    function first_name_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'first_name' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['first_name']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function last_name_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'last_name' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['last_name']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function password_name_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function password_must_be_at_least_6_characters_long()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => 'abc',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function password_confirmation_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => 'valid-password',
            'password_confirmation' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function password_confirmation_must_match_password()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => 'valid-password',
            'password_confirmation' => 'something-else',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function captcha_verification_code_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'captcha_verification_code' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['captcha_verification_code']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function captcha_verification_code_must_be_valid()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'captcha_verification_code' => 'invalid-captcha',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['captcha_verification_code']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function data_license_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'data_license' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['data_license']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function data_license_can_only_be_one_of_supported()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'data_license' => 'unsupported',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['data_license']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function image_license_is_required()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'image_license' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['image_license']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }

    /** @test */
    function image_license_can_only_be_one_of_supported()
    {
        Mail::fake();
        $this->assertEquals(0, User::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
            'image_license' => 'unsupported',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['image_license']);

        $this->assertEquals(0, User::count());
        Mail::assertNotSent(VerificationEmail::class);
        Mail::assertNotQueued(VerificationEmail::class);
    }
}
