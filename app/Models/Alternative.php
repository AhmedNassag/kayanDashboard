<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'name', 'text'
    ];


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
        return $this->morphOne(Media::class, 'mediable');
    }

    public function alternativeDetails()
    {
        return $this->hasMany(AlternativeDetail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
}
