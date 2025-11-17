<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'product_id',
        'customer_id',
        'user_id',
        'quantity',
        'in_quantity',
        'out_quantity',
        'rate',
        'total_cost',
        'product_type',
        'note',
        'created_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getBalanceAttribute()
    {
        $this->where('customer_id', $this->customer_id)
            ->where('id', '<=', $this->id)
            ->orderBy('id')
            ->sum('total_cost');
    }

    public function scopeToday(Builder $query)
    {
        $query->whereDate('created_at', today());
    }
}
