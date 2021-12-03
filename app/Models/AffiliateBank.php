<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance_available',
        'balance_withdrawn',
        'accumulated_total',
    ];
}
