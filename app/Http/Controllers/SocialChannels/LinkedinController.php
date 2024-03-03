<?php

namespace App\Http\Controllers\SocialChannels;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\linkedin;
use GuzzleHttp\Exception\RequestException;


class LinkedinController extends Controller
{
    protected $linkedinAuthService;

    public function __construct(linkedin $linkedinAuthService)
    {
        $this->linkedinAuthService = $linkedinAuthService;
    }

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

        $response = $this->linkedinAuthService->handleCallback($code, $state);

        if (isset($response['success'])) {
            return redirect()->route('root');
        } else {
            return redirect()->back()->with('error', $response['error']);
        }
    }

    public function disconnect()
    {
        User::find(auth()->user()->id)->update(['access_token' => null]);
        return redirect()->back()->with('success', 'Successfully disconnected from LinkedIn.');
    }
}
