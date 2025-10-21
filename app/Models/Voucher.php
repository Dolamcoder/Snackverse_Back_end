<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'dateStart',
        'dateEnd',
        'type', //0 là cố định, 1 là phần trăm
        'value',
    ];
    protected $dates = [
        'dateStart',
        'dateEnd',
    ];
    protected $table = 'vouchers';
}
