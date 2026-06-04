<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_precentage',
        'start_date',
        'end_date',
        'limit',
        'time_used',
        'is_active',
    ];
}
