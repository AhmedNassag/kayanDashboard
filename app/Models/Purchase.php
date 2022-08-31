<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = [
        'quantity',
        'total_price',
        'is_received'
    ];

    public function getIsReceivedAttribute()
    {
        if ($this->examinationRecord != null || $this->purchaseReturns != null)
        {
            return 1;
        }else{
            return 0;
        }
    }

    public function getQuantityAttribute()
    {
        return  $this->purchaseProducts()->sum('quantity') ;
    }

    public function getTotalPriceAttribute(){
        return ($this->price + $this->transfer_price) - ($this->discount_value + $this->other_discounts);
    }

    public function purchaseProducts(){

        return $this->hasMany(PurchaseProduct::class);
    }

    public function store(){
        return $this->belongsTo(Stock::class,'stock_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function examinationRecord(){

        return $this->hasOne(ExaminationRecord::class,'purchase_id');

    }

    public function purchaseReturns (){
        return $this->hasOne(PurchaseReturn::class,'purchase_id');
    }

}
