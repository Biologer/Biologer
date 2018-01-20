<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\ConservationList' => 'App\Policies\ConservationListPolicy',
        'App\FieldObservation' => 'App\Policies\FieldObservationPolicy',
        'App\RedList' => 'App\Policies\RedListPolicy',
        'App\Taxon' => 'App\Policies\TaxonPolicy',
        'App\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
