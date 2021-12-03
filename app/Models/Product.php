<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;

    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected $fillable = [
        'code',
        'name',
        'slug',
        'brief_description',
        'sales_unit',
        'value',
        'brand',
        'product_type',
        'weight',
        'height',
        'width',
        'length',
        'has_preparation',
        'preparation_time',
        'description',
        'status'
    ];

    public function productImage()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function productCategory()
    {
        return $this->hasMany(ProductCategory::class, 'product_id');
    }

    public function productAttribute()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function promotionP()
    {
        return $this->hasMany(Promotion::class, 'identifier');
    }
    public function promotionC()
    {
        return $this->hasMany(Promotion::class, 'identifier', 'main_category');
    }
}
