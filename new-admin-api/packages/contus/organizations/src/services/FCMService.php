<?php

namespace Packages\Contus\Organizations\Src\Services;

use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;

class FCMService
{

    public static function send($token, $notification)
    {

        $credentialsFilePath = public_path('app/json/firebase-service-account.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();

        $serviceAccount = json_decode(
            file_get_contents($credentialsFilePath),
            true
        );
        $projectId = $serviceAccount['project_id'];
        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        Http::withHeaders($headers)->post(
            "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
            [
                'to' => $token,
                'notification' => [
                    'title' => $notification['title'],
                    'body' => $notification['body']
                ]
            ]
        );
    }
}
