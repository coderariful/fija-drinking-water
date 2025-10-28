<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        Parent::__construct();
    }
    public function register(Request $request)
    {
        try {
            $validator=  Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required'],
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $data = new User();
            $data->name = $request->name;
            $data->phone = $request->phone;
            $data->user_type = 1;
            $data->password = bcrypt($request->password);
            $data ->save();
            $notification = [
                'message' =>  'Account create successfully..',
                'alert-type' => 'success'
            ];
            return back()->with($notification);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
