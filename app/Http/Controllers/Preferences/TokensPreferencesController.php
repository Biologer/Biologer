<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;

class TokensPreferencesController
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
            'tokens' => $request->user()->tokens,
        ]);
    }


    /**
     * Show page to edit user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(Request $request)
    {
        $user = $request->user();
        $token = $user->createToken('User Access Token')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Show page to edit user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeToken(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Tokens revoked'], 200);
    }
}
