<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $casts = ['saleMethods' => 'array'];


    protected $casts = [
        'status' => 'integer',
        'sell_app' => 'integer'
    ];

    protected $appends = [
        'name', 'text', 'image_path'
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

    public function selling_methods()
    {
        return $this->belongsToMany(SellingMethod::class);
    }

    public function pharmacistForm()
    {
        return $this->belongsTo(PharmacistForm::class, 'pharmacistForm_id');
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

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = $this->image;
        return asset('upload/product/' . $file_name);
    }

    // start relation

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function mainMeasurementUnit()
    {
        return $this->belongsTo(Unit::class, 'main_measurement_unit_id');
    }

    public function subMeasurementUnit()
    {
        return $this->belongsTo(Unit::class, 'sub_measurement_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function  selling_method()
    {
        return $this->belongsToMany(SellingMethod::class, 'product_selling_methods', 'product_id', 'selling_method_id');
    }

    public function purchaseProducts()
    {

        return $this->hasMany(PurchaseProduct::class);
    }

    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class, 'product_id');
    }


    public function returnProducts()
    {
        return $this->hasMany(ReturnProduct::class, 'product_id');
    }

    public function productPrice()
    {
        return $this->hasMany(ProductPricing::class, 'product_id');
    }
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function pricingHistories()
    {
        return $this->hasMany(PricingHistory::class);
    }

    public function popupAds()
    {
        return $this->hasOne(PopupAds::class);
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderStoreProduct::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function orderRetuen()
    {
        return $this->hasMany(OrderRetuen::class);
    }

    public function company()
    {
        return  $this->belongsTo(Company::class);
    }
}
