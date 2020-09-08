<?php

namespace App\Listeners;

use App\ViewGroup;
use Illuminate\Support\Facades\Cache;

class BustViewGroupsCache
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forget(ViewGroup::CACHE_GROUPS_WITH_FIRST_SPECIES);
    }
}
