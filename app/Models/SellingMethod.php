<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingMethod extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'selling_methods';

    protected $casts = [
        'status' => 'integer',
    ];


    //start relations
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    public function productPrice()
    {
        return $this->hasMany(ProductPricing::class, 'selling_method_id');
    }

    public function pricingHistories()
    {
        return $this->hasMany(PricingHistory::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function orderRetuen()
    {
        return $this->hasMany(OrderRetuen::class);
    }

}
