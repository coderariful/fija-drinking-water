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
    use HasFactory, SoftDeletes;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class,'customer_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class,'customer_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class,'customer_id');
    }

    public function dispenserUnique(): array
    {
        $productIds = $this->purchase()->where('product_type', Product::DISPENSER)->pluck('product_id');
        return Product::whereIn('id', $productIds)->pluck('name')->unique()->all();
    }

    public function dispenserAll()
    {
        $productIds = $this->purchase()->where('product_type', Product::DISPENSER)->pluck('product_id');
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
        if (array_key_exists('purchase_sum_in_quantity', $attributes) && array_key_exists('purchase_sum_out_quantity', $attributes)) {
            $totalIn = (float)$attributes['purchase_sum_in_quantity'];
            $totalOut = (float)$attributes['purchase_sum_out_quantity'];

            return $totalIn-$totalOut;
        }

        return $this->purchase()->whereProductType(Product::WATER)->select(DB::raw('IFNULL((SUM(in_quantity)-SUM(out_quantity)), 0)quantity'))->first()->quantity;
    }

    public function getDueAmountAttribute(): float
    {
        $attributes = $this->getAttributes();
        if (array_key_exists('payments_sum_amount', $attributes) && array_key_exists('sales_sum_total_cost', $attributes)) {
            $totalSales = (float)$attributes['sales_sum_total_cost'];
            $totalPayments = (float)$attributes['payments_sum_amount'];

            return $totalSales-$totalPayments;
        }

        return $this->sales()->selectRaw("IFNULL(IFNULL(SUM(total_cost), 0)-IFNULL((select sum(amount) as amount from payments where payments.customer_id=sales.customer_id), 0), 0) as amount")->first()->amount;
    }

    public function scopeToday(Builder $query)
    {
        $query->whereDate('created_at', today());
    }
}
