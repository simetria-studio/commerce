<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'tracking_id',
        'post_code',
        'state',
        'city',
        'address2',
        'address',
        'number',
        'complement',
        'phone1',
        'phone2',
        'transport',
        'price',
        'time',
        'active',
    ];
}
