<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerHistory extends Model
{
    const PENDING = 0;
    const ACCEPTED = 1;
    const REJECTED = 2;

    protected $fillable = [
        'user_id',
        'customer_id',
        'name',
        'phone',
        'address',
        'issue_date',
        'billing_type',
        'jar_rate',
        'status',
    ];

    public function original(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
