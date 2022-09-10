<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductName extends Model
{
    use HasFactory;

    protected $guarded = [];

    //start raletions
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function virtualStocks()
    {
        return $this->hasMany(VirtualStock::class,'productName_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
