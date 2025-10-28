<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    const DISPENSER = 'dispenser';
    const WATER = 'water';
    const OTHER = 'other';

    const TYPES = [
        self::DISPENSER => "Dispenser",
        self::WATER     => "Water",
        self::OTHER     => "Other",
    ];

    public array $types;


    protected $fillable = [
        'type',
        'name',
        'price',
        'sku',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'user_id');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->types = self::TYPES;
    }
}
