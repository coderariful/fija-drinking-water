<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Transaction::query()
            ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
            ->whereNull('transactions.user_id')
            ->whereNotNull('transactions.customer_id')
            ->update(['transactions.user_id' => DB::raw('customers.user_id')]);

        Transaction::query()
            ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
            ->whereRaw('transactions.user_id != customers.user_id')
            ->update(['transactions.user_id' => DB::raw('customers.user_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
