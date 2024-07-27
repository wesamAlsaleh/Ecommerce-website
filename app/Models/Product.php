<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'description',
        'price',
        'is_active',
        'in_stock',
        'is_featured',
        'on_sale',
    ];

    /**
     * the casts property allows you to define the type of data 
     * that should be cast to a native type 
     *  for example, JSON, array, integer, boolean, etc.
     * 
     * in this case, we are casting the images column from JSON (in the migration) to an array
     *  
     */
    protected $casts = [
        'images' => 'array',
    ];

    // relationships of product model

    // product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // product belongs to a brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // product has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // this product has many order items (this product is in many orders)
    }
}
