<?php

namespace App\Providers;

use App\Events\TaxonCreated;
use App\Events\TaxonDeleted;
use App\Events\UserProfileUpdated;
use App\Events\ViewGroupDeleted;
use App\Events\ViewGroupSaved;
use App\Listeners\BustViewGroupsCache;
use App\Listeners\SyncObservationsWithUserChanges;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserProfileUpdated::class => [
            // SyncObservationsWithUserChanges::class,
        ],
        ViewGroupSaved::class => [
            BustViewGroupsCache::class,
        ],
        ViewGroupDeleted::class => [
            BustViewGroupsCache::class,
        ],
        TaxonCreated::class => [
            BustViewGroupsCache::class,
        ],
        TaxonDeleted::class => [
            BustViewGroupsCache::class,
        ],
    ];
}
