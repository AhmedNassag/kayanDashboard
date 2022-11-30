<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }


    //start relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function directOrders()
    {
        return $this->morphedByMany(DirectOrders::class, 'direct_orders','order_taxes','tax_id','order_id');
    }

    public function onlineOrders()
    {
        return $this->morphedByMany(Order::class,'order', 'order_taxes', 'tax_id', 'order_id');
    }
}
