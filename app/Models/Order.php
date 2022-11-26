<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends=['area_name','city_name'];

    public function orderTax()
    {
        return $this->hasMany(OrderTax::class,'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function representative(){
        return $this->belongsTo(User::class,'representative_id');
    }

    public function products()
    {
        return $this->hasMany(CartItem::class,'order_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d  (H:i)');
    }
    public function getCityNameAttribute($value)
    {
        return $this->city->name;
    }
    public function getAreaNameAttribute($value)
    {
        return $this->area->name;
    }

}
