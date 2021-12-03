<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'payment_id',
        'issuer_id',
        'payment_method_id',
        'payment_type_id',
        'status',
        'status_detail',
        'currency_id',
        'collector_id',
        'net_received_amount',
        'total_paid_amount',
        'installments',
        'installment_amount',
        'rate_mp',
        'payer_name',
        'payer_cnpj_cpf',
        'active',
    ];
}
