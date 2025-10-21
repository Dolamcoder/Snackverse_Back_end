<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone',
        'address',
        'avatar',
        'role_id',
        'activation_token',
    ];

    // Ẩn các trường nhạy cảm khi trả về JSON
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
    ];
    public function getAddress(){
        return $this->hasMany(ShippingAddress::class, 'user_id', 'id');
    }
}
