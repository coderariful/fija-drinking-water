<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ProfileController extends Controller
{
    public function index()
    {
        try {
            $title = 'Profile Information';
            $user = Auth::user();
            $layout = $user->user_type == $user::ADMIN ? 'admin' : 'user';

            return view('common.profile.index', compact('title', 'user', 'layout'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
            ], [
                'name' => 'Name is required.',
                'email' => 'Phone is required.',

            ]);

            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('profile_photo_path')) {
                foreach ($request->profile_photo_path as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return back()->with('error', 'Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            $user = User::find($request->id);

            $profilePhoto = $user->profile_photo_path;

            if ($request->hasFile('profile_photo_path')) {
                // delete existing image
                if ($profilePhoto != null) {
                    $file = 'upload/profilePhoto/' . $profilePhoto;
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                }

                // insert new image
                $images = $request->profile_photo_path;
                foreach ($images as $img) {
                    //image name
                    $profilePhoto = time() . '.' . $img->getClientOriginalExtension();
                    // Upload image
                    $img->move(public_path('/upload/profilePhoto/'), $profilePhoto);
                }
            }

            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->profile_photo_path = $profilePhoto;
            $user->save();
            return back()->with('success', 'User Profile Updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }
    public function changePassword()
    {
        try {
            $title = 'Profile Password Change';
            $user = Auth::user();
            $layout = $user->user_type == $user::ADMIN ? 'admin' : 'user';

            return view('common.profile.password', compact('title', 'user', 'layout'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if (Hash::check($request->current_password, Auth::user()->password)) {

                DB::table('users')
                    ->where('id', Auth::user()->id)
                    ->update(['password' => Hash::make($request->password)]);

                if ($request->has('keep_me_login')) {
                    return redirect()->back()->with('success', ' Password Changed Successfully');
                } else {
                    Auth::logout();
                    return redirect('/');
                }
            } else {
                return redirect()->back()->with('error', 'Old Password do not match');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }

}
