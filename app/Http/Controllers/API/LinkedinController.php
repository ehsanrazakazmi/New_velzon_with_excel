<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;


class LinkedinController extends Controller
{
    public function signin(Request $request)
    {
        $state = encrypt(auth()->user()->id);

        $params = [
            'response_type' => 'code',
            'client_id' => '77zhhm00c33ett',
            'redirect_uri' =>
            'http://127.0.0.1:8080/linkedin/callback',
            'state' => $state,
            'scope' => 'openid,profile',
        ];

        //creating url for linkedin
        $url =
            'https://www.linkedin.com/oauth/v2/authorization?' .
            http_build_query($params);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $code = $request->code;
        $state = $request->state;

        $userID = decrypt($state);
        $user = User::find($userID);
        if (!$user) {
            // dd('User not available');
            return redirect()->back()->with('alert', 'User is not available');
        }

        $client = new Client([
            'verify' => false,
        ]);

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
                return redirect()->route('root');
            } else {
                return redirect()->back()->with('error', 'There is something fishy');
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                return redirect()->back()->with('error', $statusCode);
            } else {
                return redirect()->back('error', $e->getMessage());
            }
        }
    }

    public function disconnect()
    {
        User::find(auth()->user()->id)->update(['access_token' => null]);
        return redirect()->back()->with('success', 'Successfully disconnected from LinkedIn.');

    }
}
