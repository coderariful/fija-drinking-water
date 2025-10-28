<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'sale_id',
        'payment_id',
        'product_type',
        'in_quantity',
        'out_quantity',
        'rate',
        'created_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payments::class, 'payment_id');
    }


    public function getJarStockAttribute(): int
    {
        $qty = $this->where('customer_id', $this->customer_id)
            ->where('product_type', Product::WATER)
            ->where('id', '<=', $this->id)
            ->orderBy('id');
        $stock = $qty->selectRaw('SUM(in_quantity) as in_qty, SUM(out_quantity) as out_qty')->first();

        return intval($stock->in_qty) - intval($stock->out_qty);
    }

    public function getDueTillDateAttribute(): float
    {
        $attributes = $this->getAttributes();
        if (array_key_exists('payments_sum_amount', $attributes) && array_key_exists('sales_sum_total_cost', $attributes)) {
            $totalSales = (float)$attributes['sales_sum_total_cost'];
            $totalPayments = (float)$attributes['payments_sum_amount'];

            return $totalSales-$totalPayments;
        }

        $totalSales = $this->customer->sales()->whereDate('created_at', '<=', $this->created_at)->sum('total_cost');
        $totalPayments = $this->customer->payments()->whereDate('created_at', '<=', $this->created_at)->sum('amount');

        return $totalSales-$totalPayments;

        // return $this->sales()->selectRaw("IFNULL(IFNULL(SUM(total_cost), 0)-IFNULL((select sum(amount) as amount from payments where payments.customer_id=sales.customer_id), 0), 0) as amount")->first()->amount;
    }

    public function getNoteAttribute()
    {
        return $this->sale?->note ?? $this->payment?->note ?? null;
    }


    public function scopeToday(Builder $query)
    {
        $query->whereDate('created_at', today());
    }
}
