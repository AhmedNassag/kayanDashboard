<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function storeProducts(){
        return $this->hasMany(ProductStore::class,'product_status_id');
    }
}
