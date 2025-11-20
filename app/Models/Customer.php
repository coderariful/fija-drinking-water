<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;

    const DAILY = 'daily';
    const MONTHLY = 'monthly';

    const STATUS = [
        self::PENDING => 'Pending',
        self::APPROVED => 'Approved',
        self::REJECTED => 'Rejected'
    ];

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'issue_date',
        'jar_rate',
        'billing_type',
        'status',
        'send_sms'
    ];

    protected $casts = [
        'send_sms' => 'boolean',
        'issue_date' => 'date'
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? 'Unknown';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class,'customer_id');
    }

    public function dispenserUnique(): array
    {
        $productIds = $this->sales()->where('product_type', Product::DISPENSER)->pluck('product_id');
        return Product::whereIn('id', $productIds)->pluck('name')->unique()->all();
    }

    public function dispenserAll()
    {
        $productIds = $this->sales()->where('product_type', Product::DISPENSER)->pluck('product_id');
        $products = Product::whereIn('id', $productIds->unique())->pluck('name', 'id');
        $counts = [];
        $items = [];
        foreach ($productIds->all() as $productId) {
            isset($counts[$productId]) ? $counts[$productId] += 1 : $counts[$productId] = 1;
            $items[$productId] = "$products[$productId] x $counts[$productId]";
        }
        return collect($items);
    }

    public function getJarStockAttribute()
    {
        $attributes = $this->getAttributes();

        if (array_key_exists('jar_stock', $attributes)) {
            return (float)$attributes['jar_stock'];
        }

        if (array_key_exists('sales_sum_in_quantity', $attributes) && array_key_exists('sales_sum_out_quantity', $attributes)) {
            $totalIn = (float)$attributes['sales_sum_in_quantity'];
            $totalOut = (float)$attributes['sales_sum_out_quantity'];

            return $totalIn - $totalOut;
        }

        return $this->sales()->whereProductType(Product::WATER)
            ->select(DB::raw('IFNULL((SUM(in_quantity)-SUM(out_quantity)), 0) jar_stock'))
            ->first()->jar_stock;
    }

    public function getDueAmountAttribute(): float
    {
        $attributes = $this->getAttributes();

        if (array_key_exists('due_amount', $attributes)) {
            return (float)$attributes['due_amount'];
        }

        if (array_key_exists('sales_sum_paid_amount', $attributes) && array_key_exists('sales_sum_total_amount', $attributes)) {
            $totalSales = (float)$attributes['sales_sum_total_amount'];
            $totalPayments = (float)$attributes['sales_sum_paid_amount'];

            return $totalSales-$totalPayments;
        }

        return $this->sales()
            ->selectRaw("IFNULL(IFNULL(SUM(total_amount), 0)-IFNULL(SUM(paid_amount), 0), 0) as due_amount")
            ->first()->due_amount;
    }

    public function scopeToday(Builder $query)
    {
        $query->whereDate('created_at', today());
    }

    public function scopeWithTransactions(Builder|Customer $query): void
    {
        $query
            ->leftJoin('transactions as t', 'customers.id', '=', 't.customer_id')
            ->select([
                DB::raw('customers.*'),
                DB::raw('IFNULL(SUM(t.total_amount),0) as total_sales'),
                DB::raw('IFNULL(SUM(t.paid_amount),0) as total_paid'),
                DB::raw('IFNULL(SUM(t.in_quantity),0) as total_in_qty'),
                DB::raw('IFNULL(SUM(t.out_quantity),0) as total_out_qty'),
                DB::raw('IFNULL(SUM(t.total_amount), 0) - IFNULL(SUM(t.paid_amount), 0) as due_amount'),
                DB::raw('IFNULL(SUM(t.in_quantity), 0) - IFNULL(SUM(t.out_quantity), 0) as jar_stock'),
            ])
            ->groupBy('customers.id');
    }
}
