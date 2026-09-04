<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\WelcomeLoginController;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // paginate(5) used to cap this list at five rows while the view never
        // rendered Laravel's pager - every user past the fifth was invisible and
        // unreachable. DataTables paginates client-side, as it does for products,
        // laptops and roles.
        $users = User::with('roles')->orderBy('id', 'ASC')->get();
        $roles = Role::pluck('name','name')->all();         // retrieves all the values by given name key
        // $user = load('notifications');

        return view('User-Management.Users.list',compact('users','roles'))->with('i', ($request->input('page', 1) - 1) * 5);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles.*' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Validation issue arrived');
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $user->notify(new WelcomeNotification);

        if (! WelcomeLoginController::issueWelcomeLink($user)) {
            return redirect()->route('user.index')
                ->with('warning', 'User created, but the welcome email could not be sent. Use Resend once mail is working.');
        }

        return redirect()->route('user.index')->with('success','User created successfully');
    }
    public function edit($id)
    {
        $user = User::find(decrypt($id));
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('User-Management.Users.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        // The route carries an encrypted id. Decrypt it once here - using the
        // encrypted string in a query silently matches nothing.
        $userId = decrypt($id);
        $user   = User::findOrFail($userId);

        // Captured before the write. Changing the address means the new mailbox
        // has not been proven yet, so the account goes back to pending below.
        $emailChanged = strcasecmp($user->email, (string) $request->input('email')) !== 0;
        \Log::debug('UserController@update: emailChanged=' . ($emailChanged ? 'true' : 'false') . ', old=' . $user->email . ', new=' . (string) $request->input('email'));
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }
        else{
            $input = Arr::except($input,array('password'));
        }

        $user->update($input);

        // syncRoles replaces the whole set. The previous manual delete used the
        // encrypted id, deleted nothing, and left old roles attached - so a user
        // could never be demoted.
        $user->syncRoles($request->input('roles'));

        if (! $emailChanged) {
            return redirect()->route('user.index')->with('success', 'User updated successfully');
        }

        // A new address is an unproven address. issueWelcomeLink re-arms
        // welcome_token and must_change_password, so the account is unreachable
        // by password until someone opens the link at the address we just used.
        $sent = WelcomeLoginController::issueWelcomeLink($user);

        // Editing your own address ends your session: it now belongs to a pending
        // account, and staying signed in would let you clear the pin from the
        // password screen without ever showing you can read the new mailbox.
        if (Auth::id() === $user->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('warning', $sent
                ? 'Your email address is now ' . $user->email . '. Open the confirmation link we just sent there to get back in.'
                : 'Your email address was changed, but the confirmation email could not be sent. Ask another administrator to resend it.');
        }

        return redirect()->route('user.index')->with($sent ? 'success' : 'warning', $sent
            ? 'Email changed. A confirmation link was sent to ' . $user->email . ' - the account stays pending until they open it and set a password.'
            : 'Email changed, but the confirmation email could not be sent to ' . $user->email . '. Use Resend once mail is working.');
    }

    public function destroy($id)
    {
        User::find(decrypt($id))->delete();
        return redirect()->route('user.index')->with('success','User deleted successfully');
    }

    public function exportUser()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function importUser(Request $request)
    {
        Excel::import(new UsersImport, $request->file('file'));
    }

    /** Re-issue a welcome email for an account that has not been activated. */
    public function resendWelcome($id)
    {
        $user = User::findOrFail(decrypt($id));

        if (! $user->welcome_token && ! $user->must_change_password) {
            return redirect()->route('user.index')
                ->with('warning', $user->name . ' has already activated their account.');
        }

        if (! WelcomeLoginController::issueWelcomeLink($user)) {
            return redirect()->route('user.index')
                ->with('warning', 'Could not send the welcome email. Check the mail settings.');
        }

        return redirect()->route('user.index')
            ->with('success', 'Welcome email resent to ' . $user->email . '.');
    }
}
