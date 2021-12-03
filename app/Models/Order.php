<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'user_name',
        'user_email',
        'user_cnpj_cpf',
        'birth_date',
        'total_value',
        'cost_freight',
        'product_value',
        'pay',
        'discount',
        'coupon_value',
        'coupon',
        'active',
    ];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_number', 'order_number');
    }

    public function shippingCustomer()
    {
        return $this->hasMany(ShippingCustomer::class, 'order_number', 'order_number');
    }

    public function paymentOrder()
    {
        return $this->hasMany(PaymentOrder::class, 'order_number', 'order_number');
    }
}
