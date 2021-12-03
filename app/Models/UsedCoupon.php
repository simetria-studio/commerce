<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'coupon',
        'discount',
        'start_date',
        'final_date',
    ];
}
