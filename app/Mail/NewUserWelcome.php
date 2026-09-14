<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

/**
 * Onboarding email for an account an administrator just created.
 *
 * Carries a one-time, expiring, signed "Sign in" link and nothing else. The
 * link is the only way into a new account, so no password is ever emailed.
 */
class NewUserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    /** How long the one-time sign-in link stays valid. */
    public const LINK_TTL_HOURS = 48;

    public $user;
    public $plainToken;

    public function __construct(User $user, $plainToken)
    {
        $this->user       = $user;
        $this->plainToken = $plainToken;
    }

    public function build()
    {
        $roles = $this->user->getRoleNames();

        $signInUrl = URL::temporarySignedRoute(
            'welcome.login',
            now()->addHours(self::LINK_TTL_HOURS),
            ['user' => $this->user->id, 'token' => $this->plainToken]
        );

        return $this->subject('Welcome to ' . config('app.name'))
            ->view('emails.new-user-welcome', [
                'user'              => $this->user,
                'appName'           => config('app.name'),
                'roleLabel'         => $roles->isNotEmpty() ? $roles->implode(', ') : 'a team member',
                'signInUrl'         => $signInUrl,
                'loginUrl'          => route('login'),
                'expiresInHours'    => self::LINK_TTL_HOURS,
            ]);
    }
}
