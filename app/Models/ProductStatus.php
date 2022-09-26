<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function storeProducts(){
        return $this->hasMany(StoreProduct::class,'product_status_id');
    }

}
