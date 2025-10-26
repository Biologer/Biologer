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


    /**
     * Send a data-only message to all devices of a user via a per-user topic.
     *
     * Android should subscribe to topic: "user_{userId}" after login.
     *
     * @param  int|string  $userId
     * @param  string      $eventType  e.g. "notification_created" | "notification_read"
     * @param  array       $data       arbitrary key/value payload (will be string-cast)
     * @return bool
     */
    public static function sendToUser($userId, string $eventType, array $data = []): bool
    {
        $projectId = config('services.firebase.project_id');
        $accessToken = self::accessToken();
        if (! $projectId || ! $accessToken) {
            Log::error('FCM: missing project_id or access token');

            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
        $topic = 'user_'.$userId;

        // Ensure scalar values are strings to satisfy FCM "data" contract
        $stringData = [];
        foreach ($data as $k => $v) {
            if (is_null($v)) {
                $stringData[$k] = '';
            } elseif (is_scalar($v)) {
                $stringData[$k] = (string) $v;
            } else {
                // Encode non-scalars as JSON strings
                $stringData[$k] = json_encode($v);
            }
        }

        $payload = [
            'message' => [
                'topic' => $topic,
                'data' => array_merge([
                    'timestamp' => (string) now('UTC')->timestamp,
                    'type' => $eventType,
                ], $stringData),
            ],
        ];

        $res = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        Log::info('FirebaseV1 sendToUser', [
            'topic' => $topic,
            'type' => $eventType,
            'status' => $res->status(),
            'body' => $res->body(),
        ]);

        if ($res->failed()) {
            Log::error('FCM v1 sendToUser error', [
                'topic' => $topic,
                'type' => $eventType,
                'status' => $res->status(),
                'body' => $res->body(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * Convenience: push that a new DB notification was created for the user.
     */
    public static function sendNotificationCreated($userId, string $notificationId, ?string $type = null, ?string $updatedAtIso = null): bool
    {
        return self::sendToUser($userId, 'notification_created', [
            'notification_id' => $notificationId,
            'notification_type' => (string) ($type ?? ''),
            'updated_at' => (string) ($updatedAtIso ?? ''),
        ]);
    }

    /**
     * Used to get localized notification message.
     */
    public static function sendToUserTopic(string $topic, string $eventType, array $data = []): bool
    {
        $projectId = config('services.firebase.project_id');
        $accessToken = self::accessToken();
        if (! $projectId || ! $accessToken) {
            Log::error('FCM: missing project_id or access token');

            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // Ensure all values are strings
        $stringData = [];
        foreach ($data as $k => $v) {
            if (is_null($v)) {
                $stringData[$k] = '';
            } elseif (is_scalar($v)) {
                $stringData[$k] = (string) $v;
            } else {
                $stringData[$k] = json_encode($v);
            }
        }

        $payload = [
            'message' => [
                'topic' => $topic,
                'data' => array_merge(['type' => $eventType], $stringData),
            ],
        ];

        try {
            $res = Http::withToken($accessToken)->acceptJson()->post($url, $payload);
        } catch (\Throwable $e) {
            Log::error('FCM network error', ['error' => $e->getMessage()]);

            return false;
        }

        Log::info('FirebaseV1 sendToUserTopic', [
            'topic' => $topic,
            'type' => $eventType,
            'status' => $res->status(),
            'body' => $res->body(),
        ]);

        return $res->successful();
    }

    /**
     * Convenience: push that a DB notification was marked as read for the user.
     */
    public static function sendNotificationRead($userId, string $notificationId, ?string $readAtIso = null, ?string $updatedAtIso = null): bool
    {
        return self::sendToUser($userId, 'notification_read', [
            'notification_id' => $notificationId,
            'read_at' => (string) ($readAtIso ?? ''),
            'updated_at' => (string) ($updatedAtIso ?? ''),
        ]);
    }

    public static function sendToAnnouncement(string $baseTopic, string $title, string $body, array $data = []): bool
    {
        $projectId = config('services.firebase.project_id');
        $accessToken = self::accessToken();
        if (! $projectId || ! $accessToken) {
            Log::error('FCM: missing project_id or access token');

            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // Expect translations in the $data array as associative array:
        // e.g. ['translations' => ['en' => ['title' => '...', 'message' => '...'], 'sr' => [...]]]
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Always include default / English fallback
        if (empty($translations['en'])) {
            $translations['en'] = ['title' => $title, 'message' => $body];
        }

        // Build a JSON payload containing all translations in one data field
        $payload = [
            'message' => [
                'topic' => $baseTopic,
                'data' => array_merge([
                    'type' => 'announcement',
                    'announcement_id' => (string) ($data['announcement_id'] ?? ''),
                    'translations' => json_encode($translations, JSON_UNESCAPED_UNICODE),
                ], array_map('strval', $data)),
            ],
        ];

        try {
            $res = Http::withToken($accessToken)
                ->acceptJson()
                ->post($url, $payload);
        } catch (\Throwable $e) {
            Log::error('FCM v1 sendToAnnouncement network error', ['error' => $e->getMessage()]);

            return false;
        }

        Log::info('FirebaseV1 sendToAnnouncement', [
            'topic' => $baseTopic,
            'status' => $res->status(),
            'body' => $res->body(),
        ]);

        if ($res->failed()) {
            Log::error('FCM v1 sendToAnnouncement error', [
                'status' => $res->status(),
                'body' => $res->body(),
            ]);

            return false;
        }

        return true;
    }
}
