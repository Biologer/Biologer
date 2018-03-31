<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::filter(request())->sortByName()->paginate(10)->makeHidden([
            // We want to show just id, full name and email.
            'first_name', 'last_name', 'settings', 'institution', 'roles',
        ]);

        return UserResource::collection($users);
    }
}
