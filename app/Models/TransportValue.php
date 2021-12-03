<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_company_id',
        'weight',
        'height',
        'width',
        'length',
        'price',
        'value',
        'state',
        'city',
        'time'
    ];
}
