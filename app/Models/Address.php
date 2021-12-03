<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sequence',
        'post_code',
        'state',
        'city',
        'address2',
        'address',
        'number',
        'complement',
        'phone1',
        'phone2',
    ];
}
