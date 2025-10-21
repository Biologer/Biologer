<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseV1
{
    private static function accessToken(): ?string
    {
        $credsPath = config('services.firebase.credentials');
        if (! is_readable($credsPath)) {
            Log::error('FCM: credentials file not readable: '.$credsPath);

            return null;
        }

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $creds = new ServiceAccountCredentials($scopes, $credsPath);
        $token = $creds->fetchAuthToken();

        return $token['access_token'] ?? null;
    }

    public static function sendToToken(string $deviceToken, string $title, string $body, array $data = []): bool
    {
        $projectId = config('services.firebase.project_id');
        $accessToken = self::accessToken();
        if (! $projectId || ! $accessToken) {
            Log::error('FCM: missing project_id or access token');

            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        Log::info('FirebaseV1 response', ['status' => $response->status(), 'body' => $response->body()]);

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                // optional key/value payload for your app logic:
                'data' => array_map('strval', $data),
            ],
        ];

        $res = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($res->successful()) {
            return true;
        }

        Log::error('FCM v1 error', ['status' => $res->status(), 'body' => $res->body()]);

        return false;
    }
}
