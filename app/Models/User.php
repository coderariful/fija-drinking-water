<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ADMIN = 0;
    const EMPLOYEE = 1;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'user_type',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [];

    public function getPhotoUrlAttribute(): string
    {
        return asset($this->profile_photo_path);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->user_type==USER_ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->user_type==USER_EMPLOYEE;
    }

    public function scopeWithTransactions(Builder|Customer $query): void
    {
        $query->leftJoin('customers as c', 'users.id', '=', 'c.user_id')
            ->leftJoin('transactions as t', 'c.id', '=', 't.customer_id')
            ->select([
                DB::raw('users.*'),
                DB::raw('IFNULL(SUM(t.total_amount),0) as total_sales'),
                DB::raw('IFNULL(SUM(t.paid_amount),0) as total_paid'),
                DB::raw("IFNULL(SUM(CASE WHEN t.product_type = 'water' THEN t.in_quantity ELSE 0 END),0) as total_in_qty"),
                DB::raw("IFNULL(SUM(CASE WHEN t.product_type = 'water' THEN t.out_quantity ELSE 0 END),0) as total_out_qty"),
                DB::raw('IFNULL(SUM(t.total_amount), 0) - IFNULL(SUM(t.paid_amount), 0) as due_amount'),
                DB::raw("IFNULL(SUM(CASE WHEN t.product_type = 'water' THEN t.in_quantity ELSE 0 END), 0) - IFNULL(SUM(CASE WHEN t.product_type = 'water' THEN t.out_quantity ELSE 0 END), 0) as jar_stock"),
            ])
            ->groupBy('users.id');
    }
}
