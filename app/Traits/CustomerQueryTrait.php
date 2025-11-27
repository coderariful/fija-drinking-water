<?php

namespace App\Traits;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait CustomerQueryTrait
{
    private function addSelectStockSubquery(string $product_type): Builder|Transaction
    {
        return Transaction::from('transactions as t2')
            ->selectRaw('IFNULL(SUM(t2.in_quantity),0)-IFNULL(SUM(t2.out_quantity),0) AS jar_stock')
            ->whereColumn('t2.customer_id', 'transactions.customer_id')
            ->where('t2.product_type', $product_type)
            ->whereColumn('t2.created_at', '<=', 'transactions.created_at')
            ->orderBy(DB::raw('TIME(t2.created_at)'))
            ->orderBy('t2.id')
            ->limit(1);
    }

    private function addSelectDueTillDateSubquery(): Builder|Transaction
    {
        return Transaction::from('transactions as t2')
            ->selectRaw('IFNULL(SUM(t2.total_amount),0)-IFNULL(SUM(t2.paid_amount),0) AS due_till_date')
            ->whereColumn('t2.customer_id', 'transactions.customer_id')
            ->whereColumn('t2.created_at', '<=', 'transactions.created_at')
            ->orderBy(DB::raw('TIME(t2.created_at)'))
            ->orderBy('t2.id')
            ->limit(1);
    }
}
