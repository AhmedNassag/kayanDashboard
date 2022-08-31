<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingMethod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = ['sale_methods'];

    //start relations
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
