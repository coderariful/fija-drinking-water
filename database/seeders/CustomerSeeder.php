<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Customer::factory(40)->create();

         /** Customer History */
//        $customers = Customer::all();
//        $product = Product::where('type', PRODUCT_WATER)->first();

        /* foreach ($customers as $customer) {
//            $date =

            $sale = Transaction::create([
                'customer_id'  => $customer->id,
                'user_id'      => auth()->user()->id,
                'product_type' => $product->type,
                'created_at' => $this->date,
                'product_id' => $product->id,
                'quantity',
                'rate',
                'total_cost',
            ] );

            if (rand(0,1)) {
                $payment = Payments::create([
                    'customer_id' => $customer->id,
                    'user_id'     => auth()->user()->id,
                    'amount'      => $this->pay_amount,
                    'note'        => $this->note,
                    'created_at' => $this->date,
                ]);
            }

            Purchase::create([
                'customer_id'  => $customer->id,
                'product_id'   => $product->id,
                'sale_id'      => $sale->id,
                'payment_id'   => $payment->id ?? null,
                'product_type' => $product->type,
                'in_quantity'  => $this->quantity,
                'out_quantity' => 0,
                'rate'         => $customer->jar_rate,
                'created_at' => $this->date,
            ]);
        } */
    }
}
