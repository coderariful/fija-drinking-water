<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Traits\CustomerTrait;
use App\Traits\SendSmsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CustomersController extends Controller
{
    use SendSmsTrait, CustomerTrait;

    public function index(Request $request)
    {
        $status_list = [
            'pending' => CUSTOMER_PENDING,
            'approved' => CUSTOMER_APPROVED,
            'rejected' => CUSTOMER_REJECTED,
        ];
        try {
            $title = 'All Customers';

            $status = $status_list[$request->get('status', 'approved')];

            return view('user.customer.index', compact('title', 'status'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function create()
    {
        try {
            $title = 'Add new customer';
            $dispensers = Product::where('type', Product::DISPENSER)->get();

            return view('user.customer.create', compact('title', 'dispensers'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function store(Request $request)
    {
        // return $request->all();
        try {
            $validator =  Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'min_digits:10', 'max_digits:13'],
                'billing_type' => ['required'],
                'jar_rate' => ['required', 'numeric'],
                'dispenser' => ['nullable']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->storeRequestCustomer($request);

            return redirect()->route('user.customer.index')->with('success', 'Customer create successfully');
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function edit(Customer $customer)
    {
        try {
            $title = 'Edit customer';
            return view('user.customer.edit', compact('title', 'customer'));
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
    public function update(Request $request, Customer $customer)
    {
        try {
            $validator =  Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'min_digits:10', 'max_digits:13'],
                'billing_type'=>['required'],
                'jar_rate'=>['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->updateRequestCustomer($request, $customer);

            return redirect()->route('user.customer.index')->with('success', 'Customer updated successfully.');
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
}
