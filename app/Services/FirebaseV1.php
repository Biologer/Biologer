<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                'title' => $title,
                'body'  => $body,
            ],
            'data' => array_map('strval', $data),
            ],
        ];

    $response = Http::withToken($accessToken)
        ->acceptJson()
        ->post($url, $payload);

    Log::info('FirebaseV1 response', [
        'status' => $response->status(),
        'body'   => $response->body(),
    ]);

        if ($response->successful()) {
            return true;
        }

        Log::error('FCM v1 error', ['status' => $response->status(), 'body' => $response->body()]);

        return false;
    }

    public static function sendToAnnouncement(string $baseTopic, string $title, string $body, array $data = []): bool
    {
        $projectId   = config('services.firebase.project_id');
        $accessToken = self::accessToken();
        if (! $projectId || ! $accessToken) {
            Log::error('FCM: missing project_id or access token');
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        $success = true;

        // If no translations provided, send a default version
        if (empty($translations)) {
            $translations = [app()->getLocale() => ['title' => $title, 'message' => $body]];
        }

        foreach ($translations as $locale => $t) {
            $localeTitle = $t['title'] ?? $title;
            $localeBody  = $t['message'] ?? $body;
            $topic       = "{$baseTopic}_{$locale}";

            $payload = [
                'message' => [
                    'topic' => $topic,
                    'data' => array_merge([
                        'type'            => 'announcement',
                        'title'           => (string) $localeTitle,
                        'body'            => strip_tags((string) $localeBody),
                        'locale'          => (string) $locale,
                        'announcement_id' => (string) ($data['announcement_id'] ?? ''),
                    ], array_map('strval', $data)),
                ],
            ];

            $res = Http::withToken($accessToken)
                ->acceptJson()
                ->post($url, $payload);

            Log::info('FirebaseV1 sendToAnnouncement', [
                'topic'  => $topic,
                'locale' => $locale,
                'status' => $res->status(),
                'body'   => $res->body(),
            ]);

            if ($res->failed()) {
                $success = false;
                Log::error('FCM v1 locale error', [
                    'locale' => $locale,
                    'status' => $res->status(),
                    'body'   => $res->body(),
                ]);
            }
        }

        return $success;
    }

}
