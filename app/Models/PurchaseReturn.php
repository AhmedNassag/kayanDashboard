<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $appends = [
        'total_price',
    ];


    public function getTotalPriceAttribute(){
        return $this->returnProducts->sum('price');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function store(){
        return $this->belongsTo(Stock::class,'stock_id');
    }

    public function returnProducts(){
        return $this->hasMany(ReturnProduct::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}
