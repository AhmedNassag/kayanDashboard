<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductName extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //start raletions
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
