<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Session-cookie authentication for the Next.js frontend.
 *
 * This is Sanctum's SPA mode, not token mode: the frontend first calls
 * /sanctum/csrf-cookie, then posts here, and the resulting session cookie
 * authenticates every later request. No token is ever handed to JavaScript,
 * which is the entire point - an XSS bug cannot walk off with the session.
 */
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        $user = Auth::user();

        // Mirrors LoginController::attemptLogin. An account that has never been
        // activated is unreachable by password no matter which door it knocks
        // on - if the API skipped this, the Next.js login would be a bypass for
        // the whole email-confirmation flow.
        if ($user->welcome_token) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message'          => 'This account has not been activated yet. Please use the sign-in link in your welcome email.',
                'needs_activation' => true,
                'email'            => $request->input('email'),
            ], 403);
        }

        $request->session()->regenerate();

        return new UserResource($user->load('roles'));
    }

    /** The frontend calls this on mount to decide between app and login screen. */
    public function me(Request $request)
    {
        return new UserResource($request->user()->load('roles'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Signed out.']);
    }
}
