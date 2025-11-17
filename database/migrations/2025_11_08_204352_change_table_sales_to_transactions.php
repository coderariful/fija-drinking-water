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
        Schema::rename('sales', 'transactions');
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('type')->default('sale')->after('id')->comment('sale/payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('type');
            });
            Schema::rename('transactions', 'sales');
        }
    }
};
