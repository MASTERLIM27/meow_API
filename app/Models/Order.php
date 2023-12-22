<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'status',
        'grand_total',
        'item_count',
        'is_paid',
        'payment_method'
    ];

    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class);
    // }
}
