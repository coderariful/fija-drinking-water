<?php

namespace App\Traits;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\CustomerHistory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
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

        if ($request->has('due_amount') && $request->get('due_amount') > 0) {
            $sale = Sale::create([
                'product_id' => $product->id,
                'customer_id' => $customer->id,
                'user_id' => $customer->user_id,
                'quantity' => $request->get('jar_stock', 0),
                'rate' => $customer->jar_rate,
                'total_cost' => $request->get('due_amount'),
                'product_type' => Product::WATER,
                'created_at' => $customer->issue_date,
            ]);
        }

        if ($request->has('jar_stock') && $request->get('jar_stock') > 0) {
            Purchase::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'sale_id' => $sale->id ?? null,
                'payment_id' => null,
                'product_type' => Product::WATER,
                'in_quantity' => $request->get('jar_stock'),
                'out_quantity' => 0,
                'rate' => $customer->jar_rate,
                'created_at' => $customer->issue_date,
            ]);
        }

        if ($request->has('dispenser') && $request->get('dispenser') != '') {
            $dispenser = Product::where('type', Product::DISPENSER)->find($request->get('dispenser'));
            if($dispenser) {
                $sale = Sale::create([
                    'product_id' => $dispenser->id,
                    'customer_id' => $customer->id,
                    'user_id' => $customer->user_id,
                    'quantity' => 1,
                    'rate' => $dispenser->price,
                    'total_cost' => $dispenser->price,
                    'product_type' => Product::DISPENSER,
                    'created_at' => $customer->issue_date,
                ]);
                Purchase::create([
                    'customer_id' => $customer->id,
                    'product_id' => $dispenser->id,
                    'sale_id' => $sale->id ?? null,
                    'payment_id' => null,
                    'product_type' => Product::DISPENSER,
                    'in_quantity' => 1,
                    'out_quantity' => 0,
                    'rate' => $dispenser->price,
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
