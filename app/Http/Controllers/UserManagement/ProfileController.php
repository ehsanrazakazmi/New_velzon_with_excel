<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getprofile()
    {
        return view('User-management/Users/profile/view');
    }

    public function viewedit()
    {
        return view('User-management/Users/profile/edit');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'profile_photo_path' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Validation issue arrived');
        }

        $attributes = $validator->validated();

        if ($request->hasFile('profile_photo_path')) {
            $path = $request->file('profile_photo_path')->store('public/profile_pics');
            $path = str_replace('public/', '', $path);
        }


        User::where('id', Auth::user()->id)->update([

            'name'    => $attributes['name'],
            'email' => $attributes['email'],
            'profile_photo_path' => $path ?? Auth::user()->profile_photo_path,
        ]);
        return redirect('profile/view')->with('success', 'Profile has been updated ');
    }
}
