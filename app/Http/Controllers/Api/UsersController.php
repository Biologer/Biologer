<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function update(User $user)
    {
        request()->validate([
            'first_name' => ['nullable', 'string', 'max:191'],
            'last_name' => ['nullable', 'string', 'max:191'],
            'institution' => ['nullable', 'string', 'max:191'],
            'roles_ids' => ['array'],
            'roles_ids.*' => ['exists:roles,id'],
            'curated_taxa_ids' => ['array'],
            'curated_taxa_ids.*' => ['exists:taxa,id'],
            'email' => ['nullable', 'string', 'email:rfc,dns', 'max:191', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $updateData = [];

        if (request()->filled('first_name')) {
            $updateData['first_name'] = request('first_name');
        }

        if (request()->filled('last_name')) {
            $updateData['last_name'] = request('last_name');
        }

        if (request()->has('institution')) {
            $updateData['institution'] = request('institution');
        }

        if (request()->filled('email')) {
            $updateData['email'] = request('email');
        }

        if (request()->filled('password')) {
            $updateData['password'] = Hash::make(request('password'));
        }

        if (! empty($updateData)) {
            $user->update($updateData);
        }

        if (request()->has('roles_ids')) {
            $user->roles()->sync(request('roles_ids', []));
        }

        if (request()->has('curated_taxa_ids')) {
            $user->curatedTaxa()->sync(
                $user->hasRole('curator') ? request('curated_taxa_ids', []) : []
            );
        }

        if (isset($updateData['email'])) {
            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();
        }

        $user->load('roles');

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
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

    /**
     * Generate user access token.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(User $user)
    {
        $token = $user->createToken('User Access Token')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Remove all user access tokens.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeToken(User $user)
    {
        $user->tokens()->delete();

        return response()->json(['message' => 'Tokens revoked'], 200);
    }

    /**
     * Update or register the FCM token for the authenticated user.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'FCM token saved'], 200);
    }
}
