<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOtherDiscount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->hasOne(DirectOrders::class,'order_id');
    }
}
