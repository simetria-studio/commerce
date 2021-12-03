<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_name',
        'corporate_name',
        'fantasy_name',
        'cnpj_cpf',
    ];

    
    public function transportValues()
    {
        return $this->hasMany(TransportValue::class, 'shipping_company_id');
    }
}
