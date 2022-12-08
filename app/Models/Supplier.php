<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    protected $appends = ['text', 'name_supplier', 'sum_account'];

    protected $casts = ['status' => 'integer'];

    public function getNameSupplierAttribute()
    {
        return $this->name;
    }

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function refuseds()
    {
        return $this->hasMany(Refused::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function virtualStocks()
    {
        return $this->hasMany(VirtualStock::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function purchases()
    {

        return $this->hasMany(Purchase::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    //
    public function getSumAccountAttribute(): float
    {
        return $this->supplierAccounts->sum('amount');
    }

    public function supplierAccounts()
    {
        return $this->hasMany(SupplierAccount::class);
    }

    public function supplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class);
    }

    public function supplierIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierIncome::class);
    }
    //
}
