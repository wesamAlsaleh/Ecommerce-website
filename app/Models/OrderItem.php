<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    // relationships of order item model

    // order item belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // order item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class); // order item mean a product in an order, simple as that
    }
}


/**
 *  order < - > [order_item < === > product]
 */
