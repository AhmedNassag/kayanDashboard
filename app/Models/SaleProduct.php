<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function returnProducts()
    {
        return $this->hasMany(ProductReturn::class);
    }

    public function storeProducts()
    {
        return $this->hasMany(ProductStore::class);
    }
}
