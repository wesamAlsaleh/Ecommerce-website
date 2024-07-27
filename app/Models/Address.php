<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'home',
        'street',
        'block',
    ];

    // relationships of address model

    // address belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    /**
     * this function is an accessor 
     * accessors allow you to manipulate data before returning it 
     * this accessor will return the full name of the address owner 
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
