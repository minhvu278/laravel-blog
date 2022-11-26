<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }

    public function profile() {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.profile', compact('adminData'));
    }

    public function editProfile() {
        $id = Auth::user()->id;
        $editData = User::find($id);
        return view('admin.edit_profile', compact('editData'));
    }

    public function storeProfile(Request $request) {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;

        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');

            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $fileName);
            $data['profile_image'] = $fileName;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Update Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('admin.profile')->with($notification);
    }

    public function changePassword() {
        return view('admin.change_password');
    }

    public function updatePassword(Request $request) {
        $validateData = $request->validate([
           'old_password' => 'required',
           'new_password' => 'required',
           'confirm_password' => 'required|same:new_password',
        ]);

        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->old_password,$hashedPassword )) {
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->new_password);
            $users->save();

            session()->flash('message','Password Updated Successfully');
            return redirect()->back();
        } else{
            session()->flash('message','Old password is not match');
            return redirect()->back();
        }
    }
}
