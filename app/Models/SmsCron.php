<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCron extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'history_id',
        'type',
        'response',
    ];
}
