<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\Http\Resources\ObscuredUserResource;
use App\Models\User;

class UsersController
{
    public function index()
    {
        $users = User::filter(request(), [
            'name' => \App\Filters\User\NameLike::class,
        ])->sortByName()->paginate(10);

        return ObscuredUserResource::collection($users);
    }
}
