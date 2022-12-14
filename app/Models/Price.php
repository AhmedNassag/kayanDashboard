<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pharmacyPrice' => 'double',
        'publicPrice' => 'double',
        'clientDiscount' => 'double',
        'kayanDiscount' => 'double',
        'kayanProfit' => 'double',
        'product_id' => 'integer',
        'supplier_id' => 'integer',
    ];

    //start raletions
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    // public function category()
    // {
    //     return $this->belongsTo(Category::class,'category_id');
    // }

    // public function subCategory()
    // {
    //     return $this->belongsTo(SubCategory::class,'sub_category_id');
    // }

    // public function company()
    // {
    //     return $this->belongsTo(Company::class,'company_id');
    // }
    public function logs()
    {
        return $this->hasMany(ProductLog::class,'price_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

}
