<?php

namespace App\Policies;

use App\PublicationAttachment;
use App\User;
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
