<?php

namespace App\Notifications;

use App\FieldObservation;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FieldObservationForApproval extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\FieldObservation
     */
    public $fieldObservation;

    /**
     * Create a new notification instance.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    public function __construct(FieldObservation $fieldObservation)
    {
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

        if ($notifiable->settings()->get('notifications.field_observation_for_approval.database')) {
            $channels = array_merge($channels, ['broadcast', 'database']);
        }

        if ($notifiable->routeNotificationFor('fcm')) {
            $channels[] = FcmChannel::class;
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
            'contributor_name' => $this->fieldObservation->creatorName(),
        ];
    }

    /**
     * Build FCM payload for mobile push.
     */
    public function toFcm($notifiable)
    {
        $taxon = optional($this->fieldObservation->observation->taxon)->name;

        return [
            'title' => trans('notifications.field_observations.for_approval_subject', [], $notifiable->preferredLocale()),
            'body' => $taxon
                ? trans('notifications.field_observations.for_approval_message_with_taxon', ['taxonName' => $taxon], $notifiable->preferredLocale())
                : trans('notifications.field_observations.for_approval_message', [], $notifiable->preferredLocale()),
            'data' => [
                'type' => 'field_observation_for_approval',
                'field_observation_id' => (string) $this->fieldObservation->id,
                'contributor_name' => $this->fieldObservation->creatorName(),
                'taxon_name' => (string) $taxon,
            ],
        ];
    }
}
