<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->integer('quantity')->default(0)->change();
            $table->integer('rate')->nullable()->change();
            $table->string('product_type')->nullable()->change();

            // new columns
            $table->decimal('paid_amount')->after('total_cost')->default(0);

            // rename columns
            $table->renameColumn('total_cost', 'total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->integer('quantity')->default(null)->change();
            $table->integer('rate')->nullable(false)->change();
            $table->integer('product_type')->nullable(false)->change();
            $table->renameColumn('total_amount', 'total_cost');
            $table->dropColumn('paid_amount');
        });
    }
};
