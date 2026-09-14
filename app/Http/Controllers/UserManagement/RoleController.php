<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Jobs\CustomJob;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // gets the roles and permission throught the model
        $roles = Role::all();
        $permission = Permission::get();
        // this is a job that will execute in 5 seconds and send email then
        /*
        dispatch(new CustomJob())->delay(now()->setSeconds(5));
        can also be written as:
        CustomJob::dispatch()->delay(now()->setSeconds(5));
        */
        return view('User-Management.Roles.list', compact('roles','permission'));
    }

    public function store(Request $request)
    {
        //Preforming Validations
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        //Returning Error if Validation Fails
        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Validation error');
        }

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $role->syncPermissions($request->permission);

        return redirect()->back()->with('success','Role created successfully');
    }

    public function edit($id)
    {
        $roles = Role::findOrFail(decrypt($id));
        $permission = Permission::get();

        // Which permissions to tick on the form. This previously queried with
        // the encrypted route id, matched nothing, and rendered every box
        // unchecked - so saving the form wiped the role's real permissions.
        $rolePermissions = $roles->permissions
            ->pluck('id', 'id')
            ->all();

        return view('User-Management.Roles.edit',compact('roles','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable|string',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('success','Validation errors');
        }


        $role = Role::find(decrypt($id));
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('index.page')->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        Role::find(decrypt($id))->delete();
        return redirect()->route('index.page')->with('success','Role deleted successfully');
    }
}
