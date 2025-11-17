<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//DB::table('transactions as tt')->join('purchases as bt', 'tt.id', '=', 'bt.sale_id')->join('payments as pt', 'bt.payment_id', '=', 'pt.id')->whereNotNull("bt.payment_id")->whereNotNull("bt.sale_id")->update(['tt.paid_amount' => DB::raw('pt.amount')]);

/*
 $sql = "select * from `sales` as `tt`
    inner join `purchases` as `bt` on `tt`.`id` = `bt`.`sale_id`
    inner join `payments` as `pt` on `bt`.`payment_id` = `pt`.`id`
         where `bt`.`payment_id` is not null
           and `bt`.`sale_id` is not null";
*/

// select tt.rate, bt.in_quantity, bt.out_quantity, tt.total_cost, pt.amount from `sales` as `tt` inner join `purchases` as `bt` on `tt`.`id` = `bt`.`sale_id` inner join `payments` as `pt` on `bt`.`payment_id` = `pt`.`id` # where `bt`.`payment_id` is not null # and `bt`.`sale_id` is not null;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('transactions as tt')
            ->join('purchases as bt', 'tt.id', '=', 'bt.sale_id')
            ->join('payments as pt', 'bt.payment_id', '=', 'pt.id')
            ->whereNotNull("bt.payment_id")
            ->whereNotNull("bt.sale_id")
            ->update([
                'tt.paid_amount' => DB::raw('pt.amount')
            ]);

        // write a DB query to create transactions for the payments that do not have a sale_id in purchases table
        $payments = DB::table('payments as pt')
            ->leftJoin('purchases as bt', 'pt.id', '=', 'bt.payment_id')
            ->whereNull('bt.sale_id')
            ->select('pt.*')
            ->get();

        foreach ($payments as $payment) {
            // create a transaction
            DB::table('transactions')->insert([
                'type' => 'payment',
                'customer_id' => $payment->customer_id,
                'user_id' => $payment->user_id,
                'quantity' => 0,
                'total_amount' => 0,
                'rate' => 0,
                'paid_amount' => $payment->amount,
                'product_type' => 'none',
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // reverse not possible
    }
};

/*

alter table `sales` add `in_quantity` int not null default '0' after `quantity`, add `out_quantity` int not null default '0' after `in_quantity`

update `sales` as `st` inner join `purchases` as `pt` on `st`.`id` = `pt`.`sale_id` set `st`.`in_quantity` = pt.in_quantity, `st`.`out_quantity` = pt.out_quantity

rename table `sales` to `transactions`
alter table `transactions` add `type` varchar(191) not null default 'sale' comment 'sale/payment' after `id`

alter table `transactions` add `paid_amount` decimal(8, 2) not null default '0' after `total_cost`
alter table `transactions` rename column `total_cost` to `total_amount`

update `transactions` as `tt` inner join `payments` as `pt` on `tt`.`id` = `pt`.`sale_id` set `tt`.`paid_amount` = pt.amount

drop table if exists `payments`
drop table if exists `purchases`

*/
