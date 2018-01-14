<?php

namespace App\Http\Controllers\Api;

use App\Role;
use App\User;
use App\Taxon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(
                User::paginate($request->input('per_page', 15))
            );
        }

        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \App\Http\Resources\User
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\User
     */
    public function update(User $user, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'roles_ids' => 'array',
            'roles_ids.*' => [Rule::in(Role::pluck('id')->all())],
            'curated_taxa_ids' => 'array',
            'curated_taxa_ids.*' => [Rule::in(Taxon::pluck('id')->all())],
        ]);

        $user->update($request->only(['first_name', 'last_name']));

        if ($request->has('roles_ids')) {
            $user->roles()->sync($request->input('roles_ids', []));
        }

        $user->load('roles');

        if ($request->has('curated_taxa_ids')) {
            $user->curatedTaxa()->sync(
                $user->hasRole('curator') ? $request->input('curated_taxa_ids', []) : []
            );
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
