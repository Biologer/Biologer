<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\VerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificationTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_be_generated_for_user()
    {
        $user = factory(User::class)->create();

        $verificationToken = VerificationToken::generateFor($user);

        $this->assertTrue($verificationToken->user->is($user));
        $this->assertNotEmpty($verificationToken->token);
    }
}
