<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    //start raletions
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class,'shift_id');
    }

    public function refuseds()
    {
        return $this->hasMany(Refused::class);
    }

    //
    public function purchases(){

        return $this->hasMany(Purchase::class);
    }
    
    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
