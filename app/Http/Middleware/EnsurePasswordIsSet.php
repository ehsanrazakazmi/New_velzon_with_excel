<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Holds a freshly-onboarded user on the password screen until they replace the
 * password their administrator typed for them.
 */
class EnsurePasswordIsSet
{
    /** Routes the user must still be able to reach while pinned. */
    private const ALLOWED = ['password.set', 'password.set.store', 'logout'];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->must_change_password
            && !in_array($request->route() ? $request->route()->getName() : null, self::ALLOWED, true)) {
            return redirect()->route('password.set')
                ->with('warning', 'Please choose your own password before continuing.');
        }

        return $next($request);
    }
}
