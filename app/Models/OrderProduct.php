<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array',
        'project' => 'array',
    ];

    protected $fillable = [
        'order_number',
        'sequence',
        'product_id',
        'product_code',
        'product_name',
        'product_price',
        'quantity',
        'has_preparation',
        'preparation_time',
        'product_weight',
        'product_height',
        'product_width',
        'product_length',
        'product_sales_unit',
        'project_value',
        'project_width',
        'project_height',
        'project_meters',
        'attributes',
        'project',
        'discount',
        'note',
        'active',
    ];
}
