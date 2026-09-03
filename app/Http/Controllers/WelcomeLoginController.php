<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\NewUserWelcome;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WelcomeLoginController extends Controller
{
    /**
     * Consume the one-time link from the welcome email.
     *
     * Reaching this method already means Laravel's `signed` middleware accepted
     * the signature and the expiry, so the URL has not been tampered with. The
     * token check below is what makes it single-use: it is cleared here, so a
     * forwarded or leaked email cannot be replayed.
     */
    public function login(Request $request, $userId)
    {
        $user  = User::findOrFail($userId);
        $token = (string) $request->query('token', '');

        if (empty($user->welcome_token)
            || !hash_equals($user->welcome_token, hash('sha256', $token))) {
            return redirect()->route('login')
                ->with('warning', 'That sign-in link has already been used or is no longer valid. Please sign in with your email and password.');
        }

        $user->forceFill([
            'welcome_token'        => null,   // single use
            'must_change_password' => true,
        ])->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('password.set')
            ->with('success', 'Welcome aboard! Please choose a password to finish setting up your account.');
    }

    /**
     * Re-issue the welcome email from the login page.
     *
     * Always reports the same thing whether or not the address exists, so this
     * cannot be used to discover which emails have accounts. Throttled in the
     * route definition.
     */
    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->input('email'))
            ->whereNotNull('welcome_token')
            ->first();

        if ($user) {
            static::issueWelcomeLink($user);
        }

        return redirect()->route('login')->with(
            'success',
            'If that address belongs to an account awaiting activation, a new sign-in link is on its way.'
        );
    }

    /**
     * Mint a fresh single-use link and email it. Any previously issued link
     * stops working the moment this runs.
     */
    public static function issueWelcomeLink(User $user)
    {
        $plainToken = Str::random(48);

        $user->forceFill([
            'welcome_token'        => hash('sha256', $plainToken),
            'must_change_password' => true,
        ])->save();

        try {
            Mail::to($user->email)->send(new NewUserWelcome($user, $plainToken));

            return true;
        } catch (\Throwable $e) {
            Log::error('Welcome email failed for ' . $user->email . ': ' . $e->getMessage());

            return false;
        }
    }

    /** First-run password screen. */
    public function showSetPassword()
    {
        return view('auth.set-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = $request->user();
        $user->forceFill([
            'password'             => Hash::make($request->input('password')),
            'must_change_password' => false,
        ])->save();

        return redirect()->route('root')->with('success', 'Your password has been set.');
    }
}
