<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnreadNotificationsSummary extends Notification implements ShouldQueue
{
    use Queueable;

    private $unreadNotifications;

    /**
     * Create a new notification instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $unreadNotifications
     * @return void
     */
    public function __construct($unreadNotifications)
    {
        $this->unreadNotifications = $unreadNotifications;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('notifications.email.summary_subject'))
            ->markdown('emails.unread-notifications-summary', [
                'unreadNotifications' => $this->unreadNotifications->map->toUnreadSummaryMail($notifiable),
            ]);
    }
}
