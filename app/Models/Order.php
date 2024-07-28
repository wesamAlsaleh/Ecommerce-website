<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total', // i think this is the unit amount of the order
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_fee',
        'shipping_method',
        'notes',
    ];

    // relationships of order model

    // order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // order has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // this order has many order items (this order has many products)
    }


    // order has one address
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}


/**
 *  order < - > order_items < - > products
 */
