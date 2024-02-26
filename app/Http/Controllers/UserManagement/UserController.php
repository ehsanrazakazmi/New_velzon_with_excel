<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
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
        $data = User::orderBy('id','ASC')->paginate(5);
        $roles = Role::pluck('name','name')->all();         // retrieves all the values by given name key
        // $user = load('notifications');

        return view('User-Management.Users.list',compact('data','roles'))->with('i', ($request->input('page', 1) - 1) * 5);

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
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        // $request->password = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $user->notify(new WelcomeNotification);



        return redirect()->route('user.index')->with('success','User created successfully');
    }
    public function edit($id)
    {
        $id = decrypt($id);
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('User-Management.Users.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
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

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('user.index')->with('success','User updated successfully');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        User::find($id)->delete();
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
}
