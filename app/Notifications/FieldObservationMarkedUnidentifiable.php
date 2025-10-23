<?php

namespace App\Notifications;

use App\FieldObservation;
use App\Notifications\Channels\FcmChannel;
use App\User;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
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
            'curator_name' => $this->curator->full_name,
            'taxon_name' => optional($this->fieldObservation->observation->taxon)->name,
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
        $taxon = $this->fieldObservation->taxon;

        return [
            'message' => $taxon
                ? trans('notifications.field_observations.marked_as_unidentifiable_message_with_taxon', ['taxonName' => $taxon->name])
                : trans('notifications.field_observations.marked_as_unidentifiable_message'),
            'actionText' => trans('notifications.field_observations.action'),
            'actionUrl' => route('contributor.field-observations.show', $this->fieldObservation),
        ];
    }

    /**
     * Build localized FCM payload with all translations.
     */
    public function toFcm($notifiable)
    {
        $taxon = optional($this->fieldObservation->observation->taxon)->name;

        // Build translations for all supported locales
        $translations = [];
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $translations[$locale] = [
                'title' => trans('notifications.field_observations.marked_as_unidentifiable_subject', [], $locale),
                'message' => $taxon
                    ? trans('notifications.field_observations.marked_as_unidentifiable_message_with_taxon', ['taxonName' => $taxon], $locale)
                    : trans('notifications.field_observations.marked_as_unidentifiable_message', [], $locale),
            ];
        }

        return [
            // Default (server) locale display
            'title' => trans('notifications.field_observations.marked_as_unidentifiable_subject'),
            'body' => $taxon
                ? trans('notifications.field_observations.marked_as_unidentifiable_message_with_taxon', ['taxonName' => $taxon])
                : trans('notifications.field_observations.marked_as_unidentifiable_message'),

            'data' => [
                'type' => 'notification_created',
                'notification_subtype' => 'field_observation_marked_unidentifiable',
                'field_observation_id' => (string) $this->fieldObservation->id,
                'curator_name' => $this->curator->full_name,
                'taxon_name' => (string) $taxon,
                'translations' => $translations,
            ],
        ];
    }
}
