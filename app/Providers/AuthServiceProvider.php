<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Announcement' => 'App\Policies\AnnouncementPolicy',
        'App\ConservationLegislation' => 'App\Policies\ConservationLegislationPolicy',
        'App\FieldObservation' => 'App\Policies\FieldObservationPolicy',
        'App\LiteratureObservation' => 'App\Policies\LiteratureObservationPolicy',
        'App\Publication' => 'App\Policies\PublicationPolicy',
        'App\PublicationAttachment' => 'App\Policies\PublicationAttachmentPolicy',
        'App\RedList' => 'App\Policies\RedListPolicy',
        'App\Taxon' => 'App\Policies\TaxonPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\ViewGroup' => 'App\Policies\ViewGroupPolicy',
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
