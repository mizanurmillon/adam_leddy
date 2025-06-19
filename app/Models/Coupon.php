<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'percent_off',
        'duration',
        'valid_until',
        'is_active',
        'stripe_coupon_id',
        'stripe_promotion_code_id',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];
}
