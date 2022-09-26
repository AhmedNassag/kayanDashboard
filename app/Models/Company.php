<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
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
        return $this->morphOne(Media::class,'mediable');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
