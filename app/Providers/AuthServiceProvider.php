<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Announcement::class => \App\Policies\AnnouncementPolicy::class,
        \App\ConservationLegislation::class => \App\Policies\ConservationLegislationPolicy::class,
        \App\FieldObservation::class => \App\Policies\FieldObservationPolicy::class,
        \App\LiteratureObservation::class => \App\Policies\LiteratureObservationPolicy::class,
        \App\Publication::class => \App\Policies\PublicationPolicy::class,
        \App\PublicationAttachment::class => \App\Policies\PublicationAttachmentPolicy::class,
        \App\RedList::class => \App\Policies\RedListPolicy::class,
        \App\Taxon::class => \App\Policies\TaxonPolicy::class,
        \App\User::class => \App\Policies\UserPolicy::class,
        \App\ViewGroup::class => \App\Policies\ViewGroupPolicy::class,
        \App\TimedCountObservation::class => \App\Policies\TimedCountObservationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
