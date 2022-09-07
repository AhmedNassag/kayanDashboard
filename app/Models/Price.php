<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    //start raletions
    public function productName()
    {
        return $this->belongsTo(ProductName::class,'productName_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}
