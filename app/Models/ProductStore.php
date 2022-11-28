<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function saleRecord()
    {
        return $this->belongsTo(SaleRecord::class,'sale_record_id');
    }

    public function productStatus()
    {
        return $this->belongsTo(StatusProduct::class,'product_status_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }

    public function saleProduct(){
        return $this->belongsTo(SaleProduct::class,'sale_product_id');
    }
}
