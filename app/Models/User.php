<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function getPhotoUrlAttribute()
    {
        return asset($this->profile_photo_path);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'user_id');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class, 'user_id');
    }

    public function purchases(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Customer::class);
    }

    public function isAdmin(): bool
    {
        return $this->user_type==USER_ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->user_type==USER_EMPLOYEE;
    }
}
