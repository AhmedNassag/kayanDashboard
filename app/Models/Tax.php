<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $guarded = [];

    //start relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
