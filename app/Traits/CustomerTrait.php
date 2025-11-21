<?php

namespace App\Traits;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\CustomerHistory;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

trait CustomerTrait
{
    public function storeCustomer(Request $request): Customer
    {
        $customer = new Customer();
        $customer = $this->assignValues($request, $customer);
        $customer->save();

        $this->storeCustomerHistory($request, $customer);

        if ($customer->send_sms) {
            $smsParameters = SMS::getParameters($customer, 'new-customer-sms');
            $this->sendSms($customer, $smsParameters, 'new-customer-sms');
        }

        return $customer;
    }

    public function storeCustomerHistory(Request $request, Customer $customer): void
    {
        $product = Product::firstWhere('type', Product::WATER);

        $data = [];

        if ($request->has('due_amount') && $request->get('due_amount') > 0) {
            $data = [
                ...$data,
                'product_id' => $product->id,
                'customer_id' => $customer->id,
                'user_id' => $customer->user_id,
                'in_quantity' => 0,
                'out_quantity' => 0,
                'quantity' => 0,
                'rate' => $customer->jar_rate,
                'total_amount' => $request->get('due_amount'),
                'product_type' => Product::WATER,
                'created_at' => $customer->issue_date,
            ];
        }

        if ($request->has('jar_stock') && $request->get('jar_stock') > 0) {
            $data = [
                ...$data,
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'user_id' => $customer->user_id,
                'product_type' => Product::WATER,
                'in_quantity' => $request->get('jar_stock', 0),
                'out_quantity' => 0,
                'quantity' => $request->get('jar_stock', 0),
                'rate' => $customer->jar_rate,
                'created_at' => $customer->issue_date,
            ];
        }

        if (isset($data['customer_id']) && isset($data['user_id'])) {
            Transaction::create($data);
        }


        if ($request->has('dispenser') && $request->get('dispenser') != '') {
            $dispenser = Product::where('type', Product::DISPENSER)->find($request->get('dispenser'));
            if($dispenser) {
                Transaction::create([
                    'product_id' => $dispenser->id,
                    'customer_id' => $customer->id,
                    'user_id' => $customer->user_id,
                    'in_quantity' => 0,
                    'out_quantity' => 0,
                    'quantity' => 1,
                    'rate' => $dispenser->price,
                    'total_amount' => $dispenser->price,
                    'product_type' => Product::DISPENSER,
                    'paid_amount' => 0,
                    'created_at' => $customer->issue_date,
                ]);
            }
        }
    }

    public function updateCustomer(Request $request, Customer $customer): Customer
    {
        $customer = $customer->fill($request->except(['_token', '_method']));
        $customer->save();

        return $customer;
    }

    private function assignValues($request, $customer)
    {
        $customer->user_id = $request->user_id;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->issue_date = $request->issue_date;
        $customer->jar_rate = $request->jar_rate;
        $customer->billing_type = $request->billing_type;
        $customer->status = $request->status;
        $customer->send_sms = $request->send_sms;

        return $customer;
    }

    public function storeRequestCustomer(Request $request): Customer
    {
        $customer = new Customer();
        $customer->fill($request->except('_token'));
        $customer->user_id = auth()->id();
        $customer->send_sms = true;
        $customer->save();

        $this->storeCustomerHistory($request, $customer);

         $smsParameters = SMS::getParameters($customer, 'new-customer-sms');
         $this->sendSms($customer, $smsParameters, 'new-customer-sms');
        return $customer;
    }

    public function updateRequestCustomer($request, $customer): CustomerHistory
    {
        return CustomerHistory::create([
            'user_id'=> auth()->id(),
            'customer_id' => $customer->id,
        ] + $request->except('_token'));
    }
}
