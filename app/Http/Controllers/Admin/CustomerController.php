<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Traits\CustomerTrait;
use App\Traits\SendSmsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CustomerController extends Controller
{
    use SendSmsTrait, CustomerTrait;

    public function index()
    {
        try {
            $title = 'All Customers';

            return view('admin.customer.index', compact('title'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function pending()
    {
        try {
            $title = 'Pending Customers';
            $status = Customer::PENDING;

            return view('admin.customer.index', compact('title', 'status'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function rejected()
    {
        try {
            $title = 'Rejected Customers';
            $status = Customer::REJECTED;

            return view('admin.customer.index', compact('title', 'status'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function editRequest()
    {
        try {
            $title = 'Customer Edit Request';

            return view('admin.customer.edit-request', compact('title'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function create()
    {
        try {
            $title = 'Add new customer';

            $users = User::all();
            $dispensers = Product::where('type', Product::DISPENSER)->get();

            return view('admin.customer.create', compact('title', 'users', 'dispensers'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $validator =  Validator::make($request->all(), [
                'user_id'      => ['required'],
                'name'         => ['required', 'string', 'max:255'],
                'phone'        => ['required', 'numeric', 'min_digits:10', 'max_digits:13'],
                'billing_type' => ['required'],
                'issue_date'   => ['required'],
                'jar_rate'     => ['required', 'numeric'],
                'status'       => ['required'],
                'send_sms'     => ['nullable'],
                'dispenser'    => ['nullable'],
            ], [], [
                'user_id' => 'employee'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->storeCustomer($request);

            return redirect()->route('admin.customer.index')->with('success', 'Customer create successfully');
        } catch (Throwable $th) {
            flash($th->getMessage(), 'error');
            return $this->backWithError($th->getMessage())->withInput();
        }
    }
    public function edit(Customer $customer)
    {
        $title = 'Edit customer';

        $users = User::all();

        return view('admin.customer.edit', compact('title', 'customer', 'users'));
    }
    public function update(Request $request, $id)
    {
        $customer =  Customer::findOrFail($id);

        try {
            $validator =  Validator::make($request->all(), [
                'user_id'=>['required'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'min_digits:10', 'max_digits:13'],
                'billing_type'=>['required'],
                'jar_rate'=>['required', 'numeric'],
                'status'=>['required'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->updateCustomer($request, $customer);

            return redirect()->route('admin.customer.index')->with('success', 'Customer updated successfully.');
        } catch (Throwable $th) {
            throw $th;
            flash($th->getMessage(), 'error');
            return $this->backWithError($th->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            return back()->with('success', 'Customer deleted successfully.');
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

}
