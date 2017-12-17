<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use App\VerificationToken;
use App\Mail\VerificationEmail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificationEmailTest extends TestCase
{
    /** @test */
    function email_contains_the_verification_link()
    {
        $verificationToken = factory(VerificationToken::class)->make([
            'token' => 'verificationToken123',
            'user_id' => null,
        ]);

        $email = new VerificationEmail($verificationToken);

        $this->assertContains(
            url('/verify/token/verificationToken123'),
            $email->render()
        );
    }

    /** @test */
    function email_has_the_correct_subject()
    {
        $verificationToken = factory(VerificationToken::class)->make([
            'token' => 'verificationToken123',
            'user_id' => null,
        ]);

        $email = new VerificationEmail($verificationToken);

        $this->assertEquals('Verification Email', $email->build()->subject);
    }
}
