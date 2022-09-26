<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function storeProducts(){
        return $this->hasMany(ProductStore::class,'product_status_id');
    }
}
