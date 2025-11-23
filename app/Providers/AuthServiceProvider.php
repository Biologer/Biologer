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
        \App\Models\Announcement::class => \App\Policies\AnnouncementPolicy::class,
        \App\Models\ConservationLegislation::class => \App\Policies\ConservationLegislationPolicy::class,
        \App\Models\FieldObservation::class => \App\Policies\FieldObservationPolicy::class,
        \App\Models\LiteratureObservation::class => \App\Policies\LiteratureObservationPolicy::class,
        \App\Models\Publication::class => \App\Policies\PublicationPolicy::class,
        \App\Models\PublicationAttachment::class => \App\Policies\PublicationAttachmentPolicy::class,
        \App\Models\RedList::class => \App\Policies\RedListPolicy::class,
        \App\Models\Taxon::class => \App\Policies\TaxonPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\ViewGroup::class => \App\Policies\ViewGroupPolicy::class,
        \App\Models\TimedCountObservation::class => \App\Policies\TimedCountObservationPolicy::class,
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
