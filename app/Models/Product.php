<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $casts = ['saleMethods' => 'array'];

    protected $appends = [
        'name', 'text'
    ];

    public function related(){
        return $this->belongsToMany(Product::class,'related_products','product_id','related_id');
    }

    public function getTextAttribute()
    {
        return $this->nameAr;
    }

    public function getNameAttribute()
    {
        return $this->nameAr;
    }

    //start raletions
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function selling_methods()
    {
        return $this->belongsToMany(SellingMethod::class);
    }

    public function pharmacistForm()
    {
        return $this->belongsTo(PharmacistForm::class, 'pharmacistForm_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'main_measurement_unit_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function refuseds()
    {
        return $this->hasMany(Refused::class);
    }

    // public function alternativeDetails()
    // {
    //     return $this->hasMany(AlternativeDetail::class);
    // }
    //
    public function mainMeasurementUnit()
    {
        return $this->belongsTo(Unit::class, 'main_measurement_unit_id');
    }

    public function subMeasurementUnit()
    {
        return $this->belongsTo(Unit::class, 'sub_measurement_unit_id');
    }

    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class, 'product_id');
    }

    public function returnProducts()
    {
        return $this->hasMany(ReturnProduct::class, 'product_id');
    }

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class);
    }
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
