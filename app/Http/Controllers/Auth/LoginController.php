<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * An account created by an administrator stays closed until the person
     * follows the link in their welcome email. That is what proves they
     * actually control the mailbox - a password passed along by hand does not.
     *
     * The credentials are checked first, so this never reveals whether an
     * address exists to someone guessing.
     */
    protected function attemptLogin(Request $request)
    {
        // AuthenticatesUsers is a trait, not a parent class, so parent:: does
        // not reach its attemptLogin(). This is what that method does.
        $attempt = $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );

        if (! $attempt) {
            return false;
        }

        $user = $this->guard()->user();

        if ($user && $user->welcome_token) {
            $this->guard()->logout();

            // Lets the login page offer a Resend button for this address.
            $request->session()->flash('needs_activation', $user->email);

            throw ValidationException::withMessages([
                'email' => 'This account has not been activated yet. Please use the sign-in link in your welcome email.',
            ]);
        }

        return true;
    }
}
