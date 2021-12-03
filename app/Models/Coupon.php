<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'discount_type',
        'value',
        'min_value',
        'max_value',
        'start_date',
        'final_date',
        'discount_accepted',
        'installemnts',
        'user_id',
        'active',
    ];
}
