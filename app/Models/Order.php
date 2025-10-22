<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'total_price',
        'shipping_address_id',
        'voucher_id',
    ];
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function orderStatusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }
}
