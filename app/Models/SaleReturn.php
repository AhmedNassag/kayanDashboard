<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $appends = ['total_price'];


    public function getTotalPriceAttribute()
    {
        return $this->returnProducts->sum('price');
    }


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }


    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }


    public function returnProducts()
    {
        return $this->hasMany(ProductReturn::class);
    }


    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }
}
