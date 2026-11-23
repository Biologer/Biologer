<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;

class TokenPreferencesController
{
    /**
     * Display user's license preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        return view('preferences.token', [
            'tokens' => $request->user()->tokens()
                ->whereNotNull('name')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn ($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'revoked' => (bool) $t->revoked,
                    'expires_at' => optional($t->expires_at)->toIso8601String(),
                    'created_at' => optional($t->created_at)->toIso8601String(),
                ]),
        ]);
    }


    /**
     * Show page to edit user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|min:10|max:255',
        ]);


        $existingToken = $user->tokens()
            ->where('revoked', false)
            ->whereNotNull('name')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingToken) {
            return response()->json([
                'message' => 'You already have a valid token.',
            ], 400);
        }

        $tokenResult = $user->createToken($validated['name']);

        return response()->json([
            'token' => $tokenResult->accessToken,
            'id' => $tokenResult->token->id,
            'expires_at' => $tokenResult->token->expires_at,
        ]);
    }

    /**
     * Show page to edit user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'token_id' => 'required|string|max:100',
        ]);

        $token = $user->tokens()->where('id', $request->input('token_id'))->first();

        if ($token && ! $token->revoked) {
            $token->revoke();

            return response()->json(['message' => 'Token revoked successfully']);
        }

        return response()->json(['message' => 'Token not found or already revoked'], 404);
    }
}
