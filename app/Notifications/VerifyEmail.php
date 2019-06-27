<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $expires = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60));

        $verificationUrl = $this->verificationUrl($notifiable, $expires);

        return (new MailMessage)
            ->subject(Lang::getFromJson('Verify Email Address'))
            ->line(Lang::getFromJson('Please click the button below to verify your email address.'))
            ->action(Lang::getFromJson('Verify Email Address'), $verificationUrl)
            ->line(Lang::getFromJson('This link will expire at :expiresAt. You can always request another verification link to be sent when you log in.', ['expiresAt' => $expires->format('d.m.Y H:i')]))
            ->line(Lang::getFromJson('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  \DateTimeInterface  $expires
     * @return string
     */
    protected function verificationUrl($notifiable, $expires)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            $expires,
            ['id' => $notifiable->getKey()]
        );
    }
}
