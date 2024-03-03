<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class linkedin
{
    public function handleCallback($code, $state)
    {
        $userID = decrypt($state);
        $user = User::find($userID);

        if (!$user) {
            return ['error' => 'User is not available'];
        }

        $client = new Client(['verify' => false]);

        try {
            $response = $client->post('https://www.linkedin.com/oauth/v2/accessToken', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => 'http://127.0.0.1:8080/linkedin/callback',
                    'client_id' => '77zhhm00c33ett',
                    'client_secret' => 'ltxkzi5Pd7xxLGYO',
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (isset($responseData['access_token'])) {
                $accessToken = $responseData['access_token'];
                $user->access_token = $accessToken;
                $user->save();
                return ['success' => true];
            } else {
                return ['error' => 'There is something fishy'];
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                return ['error' => $statusCode];
            } else {
                return ['error' => $e->getMessage()];
            }
        }
    }
}
