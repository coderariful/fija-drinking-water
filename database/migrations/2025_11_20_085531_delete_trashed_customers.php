<?php

use App\Models\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        $customer = new class extends Customer {
            use SoftDeletes;
            protected $connection = 'mysql';
            protected $table = 'customers';
        };
        $customer->query()->onlyTrashed()->forceDelete();
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

/*
alter table `sales` add `in_quantity` int not null default '0' after `quantity`, add `out_quantity` int not null default '0' after `in_quantity`;
update `sales` as `st` inner join `purchases` as `pt` on `st`.`id` = `pt`.`sale_id` set `st`.`in_quantity` = pt.in_quantity, `st`.`out_quantity` = pt.out_quantity;
rename table `sales` to `transactions`;
alter table `transactions` add `type` varchar(191) not null default 'sale' comment 'sale/payment' after `id`;
ALTER TABLE transactions CHANGE COLUMN total_cost total_amount DECIMAL(10, 2);
update `transactions` as `tt` inner join `purchases` as `bt` on `tt`.`id` = `bt`.`sale_id` inner join `payments` as `pt` on `bt`.`payment_id` = `pt`.`id` set `tt`.`paid_amount` = pt.amount where `bt`.`payment_id` is not null and `bt`.`sale_id` is not null;
select `pt`.* from `payments` as `pt` left join `purchases` as `bt` on `pt`.`id` = `bt`.`payment_id` where `bt`.`sale_id` is null;
drop table if exists `payments`;
drop table if exists `purchases`;
delete from `customers` where `customers`.`deleted_at` is not null;
*/
