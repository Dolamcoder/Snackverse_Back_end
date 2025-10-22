<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;
    protected $table = 'order-status-histories';
    protected $fillable = [
        'order_id',
        'status',
        'note',
    ];
}
