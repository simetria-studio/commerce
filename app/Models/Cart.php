<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'row_id',
        'name',
        'price',
        'quantity',
        'attributes',
        'active',
    ];
}
