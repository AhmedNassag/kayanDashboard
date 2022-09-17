<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['price'];


    public function getPriceAttribute()
    {
        return $this->saleProduct->price_after_discount * $this->quantity ;
    }

    public function saleRecords()
    {
        return $this->belongsTo(ProductReturn::class,'sale_return_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function saleProduct(){
        return $this->belongsTo(SaleProduct::class,'sale_product_id');
    }
}
