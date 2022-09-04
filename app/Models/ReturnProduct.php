<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'price',
    ];


    public function getPriceAttribute(){
        return $this->purchaseProduct->price_after_discount * $this->quantity ;
    }

    public function examinationRecords(){
        return $this->belongsTo(PurchaseReturn::class,'purchase_return_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function purchaseProduct(){
        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id');
    }
}

