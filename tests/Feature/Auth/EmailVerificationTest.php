<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function show_page_to_request_email_verification_link()
    {
        $this->actingAs(
            User::factory()->unverified()->create()
        )->get('/email/verify')->assertSee(__('Verify Your Email Address'));
    }

    #[Test]
    public function can_resend_verification_email()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this
            ->actingAs($user)
            ->from('/email/verify')
            ->post('/email/resend')
            ->assertRedirect('/email/verify')
            ->assertSessionDoesntHaveErrors();

        Notification::assertSentToTimes($user, VerifyEmail::class, 1);
    }

    #[Test]
    public function verify_email_of_user_that_is_logged_in()
    {
        $user = User::factory()->unverified()->create();

        $this
            ->actingAs($user)
            ->get($this->createVerificationLink($user))
            ->assertRedirect('/contributor')
            ->assertSessionDoesntHaveErrors()
            ->assertSessionHas('verified', true);
    }

    #[Test]
    public function verify_email_of_user_that_is_not_logged_in()
    {
        $user = User::factory()->unverified()->create();

        $this
            ->get($this->createVerificationLink($user))
            ->assertRedirect('/login')
            ->assertSessionDoesntHaveErrors()
            ->assertSessionHas('verified', true);
    }

    #[Test]
    public function cannot_verify_email_using_some_elses_link_when_logged_in()
    {
        $user = User::factory()->unverified()->create();

        $this
            ->actingAs(User::factory()->unverified()->create())
            ->get($this->createVerificationLink($user))
            ->assertForbidden();
    }

    protected function createVerificationLink(User $user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Date::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }
}
