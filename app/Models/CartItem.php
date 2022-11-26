<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];
    protected $appends = ['supplier_name', 'product_name_ar', 'product_name_en'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function getSupplierNameAttribute()
    {
        return $this->supplier->name;
    }
    public function getProductNameArAttribute()
    {
        return $this->product->nameAr;
    }
    public function getProductNameEnAttribute()
    {
        return $this->product->nameEn;
    }
}
