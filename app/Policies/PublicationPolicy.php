<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicationPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    public function update(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }
}
