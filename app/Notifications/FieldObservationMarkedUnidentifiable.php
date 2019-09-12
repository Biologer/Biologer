<?php

namespace App\Notifications;

use App\FieldObservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FieldObservationMarkedUnidentifiable extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\User
     */
    public $curator;

    /**
     * @var \App\FieldObservation
     */
    public $fieldObservation;

    /**
     * Create a new notification instance.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  \App\User  $curator
     * @return void
     */
    public function __construct(FieldObservation $fieldObservation, User $curator)
    {
        $this->curator = $curator;
        $this->fieldObservation = $fieldObservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];

        if ($notifiable->settings()->get('notifications.field_observation_marked_unidentifiable.database')) {
            $channels = array_merge($channels, ['broadcast', 'database']);
        }

        if ($notifiable->settings()->get('notifications.field_observation_marked_unidentifiable.mail')) {
            $channels = array_merge($channels, [Channels\UnreadSummaryMailChannel::class]);
        }

        return $channels;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'field_observation_id' => $this->fieldObservation->id,
            'curator_name' => $this->curator->full_name,
        ];
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
            ->subject(trans('notifications.field_observations.marked_as_unidentifiable_subject'))
            ->line(trans('notifications.field_observations.marked_as_unidentifiable_message'))
            ->action(
                trans('notifications.field_observations.action'),
                route('contributor.field-observations.show', $this->fieldObservation)
            );
    }

    /**
     * Format data for summary mail.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toUnreadSummaryMail($notifiable)
    {
        return [
            'message' => trans('notifications.field_observations.marked_as_unidentifiable_message'),
            'actionText' => trans('notifications.field_observations.action'),
            'actionUrl' => route('contributor.field-observations.show', $this->fieldObservation),
        ];
    }
}
