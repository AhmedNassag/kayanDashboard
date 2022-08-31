<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function examinationRecord(){
        return $this->belongsTo(ExaminationRecord::class,'examination_record_id');
    }

    public function productStatus(){
        return $this->belongsTo(ProductStatus::class,'product_status_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function store(){
        return $this->belongsTo(Stock::class,'store_id');
    }

    public function purchaseProduct(){
        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id');
    }
}
