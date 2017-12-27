<?php

namespace App\Concerns;

use App\VerificationToken;

trait Verifiable
{
    /**
     * User's email verification token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function verificationToken()
    {
        return $this->hasOne(VerificationToken::class);
    }

    /**
     * Generate verification token for the user.
     *
     * @return VerificationToken
     */
    public function generateVerificationToken()
    {
        $token = $this->verificationToken()->first();

        return $token ?: $this->verificationToken()->create([
            'token' => base64_encode($this->email.str_random(10)),
        ]);
    }

    /**
     * Mark that user has verified their email.
     *
     * @return $this
     */
    public function markAsVerified()
    {
        $this->update(['verified' => true]);

        return $this;
    }

    /**
     * Send verification email to the user.
     *
     * @return $this
     */
    public function sendVerificationEmail()
    {
        $this->verificationToken->send();

        return $this;
    }
}
