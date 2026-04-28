<?php

namespace App\Notifications;

use App\TimedCountObservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimedCountObservationEdited extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\User
     */
    public $causer;

    /**
     * @var \App\TimedCountObservation
     */
    public $timedCountObservation;

    /**
     * Create a new notification instance.
     *
     * @param  \App\TimedCountObservation  $timedCountObservation
     * @param  \App\User  $causer
     * @return void
     */
    public function __construct(TimedCountObservation $timedCountObservation, User $causer)
    {
        $this->causer = $causer;
        $this->TimedCountObservation = $timedCountObservation;
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

        if ($notifiable->settings()->get('notifications.field_observation_edited.database')) {
            $channels = array_merge($channels, ['broadcast', 'database']);
        }

        if ($notifiable->settings()->get('notifications.field_observation_edited.mail')) {
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
            'field_observation_id' => $this->TimedCountObservation->id,
            'causer_name' => $this->causer->full_name,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * NOTE: No longer used, should be deleted at some point.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('notifications.field_observations.edited_subject'))
            ->line(trans('notifications.field_observations.edited_message'))
            ->action(
                trans('notifications.field_observations.action'),
                route('contributor.field-observations.show', $this->TimedCountObservation)
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
        $taxon = $this->TimedCountObservation->taxon;

        return [
            'message' => $taxon
                ? trans('notifications.field_observations.edited_message_with_taxon', ['taxonName' => $taxon->name])
                : trans('notifications.field_observations.edited_message'),
            'actionText' => trans('notifications.field_observations.action'),
            'actionUrl' => route('contributor.field-observations.show', $this->TimedCountObservation),
        ];
    }
}
