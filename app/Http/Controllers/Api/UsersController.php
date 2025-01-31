<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Role;
use App\Taxon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $query = User::filter(request(), [
            'sort_by' => \App\Filters\SortBy::class,
            'search' => \App\Filters\User\Search::class,
        ]);

        $result = request()->has('page')
            ? $query->paginate(request('per_page', 15))
            : $query->get();

        return UserResource::collection($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // New users are created through registration form.
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function update(User $user)
    {
        request()->validate([
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'institution' => ['nullable', 'string', 'max:191'],
            'roles_ids' => ['array'],
            'roles_ids.*' => [Rule::in(Role::pluck('id')->all())],
            'curated_taxa_ids' => [
                'array',
                Rule::in(Taxon::pluck('id')->all()),
            ],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:191', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->update(request(['first_name', 'last_name', 'institution']));

        if (request()->has('roles_ids')) {
            $user->roles()->sync(request('roles_ids', []));
        }

        $user->load('roles');

        if (request()->has('curated_taxa_ids')) {
            $user->curatedTaxa()->sync(
                $user->hasRole('curator') ? request('curated_taxa_ids', []) : []
            );
        }

        if (request()->has('email')) {
            $user->update(['email' => request('email')]);

            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();
        }

        if (request()->has('password')) {
            $user->update(['password' => Hash::make(request('password'))]);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        request()->validate([
            'delete_observations' => ['boolean'],
        ]);

        $delete_observations = request()->boolean('delete_observations');

        $user->deleteAccount($delete_observations);

        $message = $delete_observations
            ? 'You account has been deleted. Your observations will be deleted shortly.'
            : 'You account has been deleted.';

        return response()->json($message, 204);
    }
}
