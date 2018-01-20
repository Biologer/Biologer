<?php

namespace App\Mail;

use App\VerificationToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationToken;

    /**
     * Create a new message instance.
     *
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
        return $this->markdown('emails.auth.verification', [
            'verificationToken' => $this->verificationToken,
        ])->subject('Verification Email');
    }
}
