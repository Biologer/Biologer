<?php

namespace App\Mail;

use App\VerificationToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\VerificationToken
     */
    public $verificationToken;

    /**
     * Create a new message instance.
     *
     * @param \App\VerificationToken $verificationToken
     * @return void
     */
    public function __construct(VerificationToken $verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = $this->verificationToken->user->settings()->get('language');

        return $this->markdown($locale.'.emails.auth.verification', [
            'verificationToken' => $this->verificationToken,
        ])->subject(__('Account Verification', [], $locale));
    }
}
