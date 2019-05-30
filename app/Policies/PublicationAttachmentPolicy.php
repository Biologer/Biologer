<?php

namespace App\Policies;

use App\User;
use App\PublicationAttachment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicationAttachmentPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    public function delete(User $user, PublicationAttachment $publicationAttachment)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }
}
