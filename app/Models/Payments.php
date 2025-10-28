<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payments extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
        'amount',
        'note',
        'created_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class, 'payment_id');
    }


    public function getBalanceAttribute()
    {
        return $this->where('customer_id', $this->customer_id)
            ->where('id', '<=', $this->id)
            ->orderBy('id')
            ->sum('amount');
    }


    public function scopeToday(Builder $query)
    {
        $query->whereDate('created_at', today());
    }
}
