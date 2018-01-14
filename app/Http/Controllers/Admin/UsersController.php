<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user->load(['roles', 'curatedTaxa']),
            'roles' => Role::all(),
        ]);
    }

    public function update(User $user, Request $request)
    {
        return route('admin.users.index');
    }
}
