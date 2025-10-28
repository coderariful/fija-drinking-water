<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        Parent::__construct();
    }
    public function dashboard()
    {
        try {
            $customerAdd = Customer::select('id', 'created_at')
                ->where('user_id', Auth::user()->id)
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            $customerCount = [];
            $customerList = [];
            foreach ($customerAdd as $key => $value) {
                $customerCount[(int)$key] = count($value);
            }
            for ($i = 1; $i <= 12; $i++) {
                if (!empty($customerCount[$i])) {
                    $customerList[$i] = $customerCount[$i];
                } else {
                    $customerList[$i] = 0;
                }
            }
            $data['customerList'] = $customerList;
            $title = 'User Dashboard';
            $customers = Customer::where('user_id', Auth::user()->id)->get();
            $activeCustomers = Customer::where('status',1)->where('user_id', Auth::user()->id);
            $deactiveCustomers = Customer::where('status',0)->where('user_id', Auth::user()->id);
            return view('user.dashboard', compact('title','customers','deactiveCustomers','activeCustomers','data'));
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }

    public function addNewCustomer()
    {
        try {
            $title = 'Add new customer';
            return view('user.customer.form', compact('title'));
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }
    public function storeNewCustomer(Request $request)
    {
        try {
            $validator=  Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required'],

            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $data = new Customer();
            $data->user_id = Auth::user()->id;
            $data->name = $request->name;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->cash_payment = $request->cash_payment;
            $data->due_payment = $request->due_payment;
            $data->jar_quantity = $request->jar_quantity;
            $data->despenser = $request->despenser;
            $data->issue_date = $request->issue_date;
            $data->status = $request->status;
            $data ->save();
            return back()->with('success', 'Customer create successfully....');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function allCustomer()
    {
        try {
            $title = 'All customer';
            $values = Customer::where('user_id',Auth::user()->id)->paginate(10);
            return view('user.customer.index', compact('title','values'));
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }
    public function editCustomer($id)
    {

        try {
            $title = 'Edit customer';
            $value = Customer::find($id);
            return view('user.customer.show', compact('title','value'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateCustomer(Request $request ,$id)
    {
        try {
            $validator=  Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required'],

            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $data =  Customer::find($id);
            $data->user_id = Auth::user()->id;
            $data->name = $request->name;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->cash_payment = $request->cash_payment;
            $data->due_payment = $request->due_payment;
            $data->jar_quantity = $request->jar_quantity;
            $data->despenser = $request->despenser;
            $data->issue_date = $request->issue_date;
            $data->status = $request->status;
            $data ->save();
            return back()->with('success', 'Customer Update successfully....');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function moneyDetails()
    {
        try {
            $title = 'Details of money';
            $totalCollectMoney = Customer::where('user_id', Auth::user()->id)->sum('cash_payment');
            $totalDueMoney = Customer::where('user_id', Auth::user()->id)->sum('due_payment');
            $totalMoney = $totalCollectMoney + $totalDueMoney;
            return view('user.money.index', compact('title','totalMoney','totalCollectMoney','totalDueMoney'));
        }catch (\Throwable $th){
            throw $th;
        }
    }
}
