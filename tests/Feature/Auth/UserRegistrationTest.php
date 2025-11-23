<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\Test;
use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Nikazooz\LaravelCaptcha\Facades\Captcha;
use Tests\TestCase;

final class UserRegistrationTest extends TestCase
{
    protected function validParams($overrides = [])
    {
        return array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'institution' => 'Faculty of Science and Mathematics',
            'password' => 'top-secret-password',
            'password_confirmation' => 'top-secret-password',
            'data_license' => 10,
            'image_license' => 20,
            'captcha_verification_code' => Captcha::code(),
            'accept' => true,
        ], $overrides);
    }

    #[Test]
    public function user_can_register_by_providing_required_information(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->post('/register', $this->validParams());

        $response->assertRedirect('/contributor');

        $this->assertTrue(auth()->check());

        User::assertCount(1);
        $user = User::first();
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('Faculty of Science and Mathematics', $user->institution);
        $this->assertTrue(Hash::check('top-secret-password', $user->password));
        $this->assertEquals(10, $user->settings()->get('data_license'));
        $this->assertEquals(20, $user->settings()->get('image_license'));

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    public function first_name_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'first_name' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['first_name']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function last_name_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'last_name' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['last_name']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function password_name_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function password_must_be_at_least_6_characters_long(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => 'abc',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function password_confirmation_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => 'valid-password',
            'password_confirmation' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function password_confirmation_must_match_password(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'password' => 'valid-password',
            'password_confirmation' => 'something-else',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function captcha_verification_code_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'captcha_verification_code' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['captcha_verification_code']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function captcha_verification_code_must_be_valid(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'captcha_verification_code' => 'invalid-captcha',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['captcha_verification_code']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function data_license_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'data_license' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['data_license']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function data_license_can_only_be_one_of_supported(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'data_license' => 'unsupported',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['data_license']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function image_license_is_required(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'image_license' => null,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['image_license']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function image_license_can_only_be_one_of_supported(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'image_license' => 'unsupported',
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['image_license']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }

    #[Test]
    public function user_is_required_to_accept_terms_of_service_and_privacy_policy(): void
    {
        Notification::fake();
        User::assertCount(0);

        $response = $this->from('/register')->post('/register', $this->validParams([
            'accept' => false,
        ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['accept']);

        User::assertCount(0);
        Notification::assertTimesSent(0, VerifyEmail::class);
    }
}
