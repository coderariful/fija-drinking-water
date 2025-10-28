<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('admin.employee.index', [
            'title' => trans('All employee'),
        ]);
    }
    public function create()
    {
        try {
            $title = 'Add new employee';
            return view('admin.employee.create', compact('title'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'unique:users'],
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
            $data->save();
            return redirect()->route('admin.employee.index')->with('success', 'Account create successfully');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function edit(User $employee)
    {
        try {
            $title = 'Edit employee';
            return view('admin.employee.edit', compact('title', 'employee'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function update(Request $request, User $employee)
    {
        try {
            $data = $this->validate($request, [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric' , 'unique:users,phone,'.$employee->id],
                'password' => 'nullable|string|min:6|confirmed',
                'password_confirmation' => 'required_with:password',
            ]);

            $employee->name = $data['name'];
            $employee->phone = $data['phone'];
            $employee->user_type = USER_EMPLOYEE;
            $employee->password = bcrypt($data['password']);
            $employee->save();

            return redirect()->route('admin.employee.index')->with('success', 'Account updated successfully.');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(User $employee)
    {
        try {
            $image_path = public_path("upload/profilePhoto/" . $employee->profile_photo_path);

            if (File::exists($image_path)) {
                //File::delete($image_path);
                @unlink($image_path);
            }

            $employee->delete();

            return $this->backWithSuccess("Employee delete successful");
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

}
