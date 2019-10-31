<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\Http\Resources\UserResource;
use App\User;

class UsersController
{
    public function index()
    {
        $users = User::filter(request(), [
            'name' => \App\Filters\User\NameLike::class,
        ])->sortByName()->paginate(10)->makeHidden([
            // We want to show just id, full name and email.
            'first_name', 'last_name', 'settings', 'institution', 'roles',
        ]);

        return UserResource::collection($users);
    }
}
