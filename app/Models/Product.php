<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
