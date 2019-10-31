<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\User;

class UsersController
{
    /**
     * List users to admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show page to edit user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user->load(['roles', 'curatedTaxa']),
            'roles' => Role::all(),
        ]);
    }
}
