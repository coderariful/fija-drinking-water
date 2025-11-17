<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// $purchasesTableMigration = require __DIR__.'/2023_02_03_105317_create_purchases_table.php';
// 2025_11_09_013226 _migrate_purchase_data_to_sales

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('purchases');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('product_type')->nullable();
            $table->integer('in_quantity')->default(0);
            $table->integer('out_quantity')->default(0);
            $table->decimal('rate')->nullable();
            $table->timestamps();
        });
    }
};


// alter table `sales` add `in_quantity` int not null default '0' after `quantity`, add `out_quantity` int not null default '0' after `in_quantity`;
// rename table `sales` to `transactions`;
// alter table `transactions` add `type` varchar(191) not null default 'sale' after `id`;
// drop table if exists `purchases`;
