<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleAdvertise extends Model
{
    protected $guarded = ["product_name_ar", "product_name_en"];
    public function setExternalAttribute($value)
    {
        $this->attributes["external"] = $value == "true" ? 1 : 0;
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
