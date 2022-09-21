<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }

    //start raletions

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function refuseds()
    {
        return $this->hasMany(Refused::class);
    }

    public function virtualStocks()
    {
        return $this->hasMany(VirtualStock::class);
    }
}
