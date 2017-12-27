<?php

namespace Tests\Unit\Mail;

use App\User;
use Tests\TestCase;
use App\VerificationToken;
use App\Mail\VerificationEmail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificationEmailTest extends TestCase
{
    protected function getVerificationToken($token = 'verificationToken123')
    {
        $verificationToken = factory(VerificationToken::class)->make([
            'token' => $token,
            'user_id' => null,
        ]);

        $verificationToken->setRelation('user', factory(User::class)->make());

        return $verificationToken;
    }

    /** @test */
    function email_contains_the_verification_link()
    {
        $email = new VerificationEmail($this->getVerificationToken());

        $this->assertContains(
            url('/verify/token/verificationToken123'),
            $email->render()
        );
    }

    /** @test */
    function email_has_the_correct_subject()
    {
        $email = new VerificationEmail($this->getVerificationToken());

        $this->assertEquals('Verification Email', $email->build()->subject);
    }
}
