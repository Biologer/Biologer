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
     * Build the FCM payload including all locales.
     */
    public function toFcm($notifiable)
    {
        $taxon = optional($this->fieldObservation->observation->taxon)->name;

        // Build translations for all supported locales using your Localization class
        $translations = [];
        foreach (\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $translations[$locale] = [
                'title' => trans('notifications.field_observations.for_approval_subject', [], $locale),
                'message' => $taxon
                    ? trans('notifications.field_observations.for_approval_message_with_taxon', ['taxonName' => $taxon], $locale)
                    : trans('notifications.field_observations.for_approval_message', [], $locale),
            ];
        }

        return [
            // Default display locale (server app locale)
            'title' => trans('notifications.field_observations.for_approval_subject'),
            'body' => $taxon
                ? trans('notifications.field_observations.for_approval_message_with_taxon', ['taxonName' => $taxon])
                : trans('notifications.field_observations.for_approval_message'),

            'data' => [
                'type' => 'notification_created',
                'notification_subtype' => 'field_observation_for_approval',
                'notification_id' => (string) $this->id,
                'field_observation_id' => (string) $this->fieldObservation->id,
                'contributor_name' => $this->fieldObservation->creatorName(),
                'taxon_name' => (string) $taxon,
                'translations' => $translations,
            ],
        ];
    }
}
