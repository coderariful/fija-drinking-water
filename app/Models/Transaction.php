<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

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
        'total_amount',
        'paid_amount',
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

    public function jarStockQuery(): Builder|Transaction
    {
        return $this->where('customer_id', $this->customer_id)
            ->where('product_type', Product::WATER)
            ->when($this->id, fn($q) => $q->where('id', '<=', $this->id))
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function getJarStockAttribute(): int
    {
        if (property_exists($this, 'jar_stock') && !is_null($this->jar_stock)) {
            return $this->jar_stock;
        }

        $query = $this->jarStockQuery();
        $stock = $query->selectRaw('SUM(in_quantity) as in_qty, SUM(out_quantity) as out_qty')->first();

        return intval($stock->in_qty) - intval($stock->out_qty);
    }

    public function getBalanceAttribute()
    {
        return $this->where('customer_id', $this->customer_id)
            ->where('id', '<=', $this->id)
            ->orderBy('id')
            ->sum('total_amount');
    }

    public function scopeToday(Builder $query): void
    {
        $query->whereDate('created_at', today());
    }

    public function scopeThisMonth(Builder $query): void
    {
        $query->where(DB::raw('MONTH(created_at)'), today()->month);
    }

    public function scopeNotObsolete(Builder $query): void
    {
        $query->whereHas('customer')->whereHas('user');
    }

    public function scopeCommonQuery(): Builder
    {
        return static::query()
            ->whereIn('customer_id', Customer::select('id'))
            ->whereIn('user_id', User::select('id'));
    }
}
