<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'hexadecimal',
        'image',
    ];

    public function variations()
    {
        return $this->hasMany(Attribute::class, 'parent_id');
    }
}
