<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = ["city_name"];
    public function city(){
        return $this->belongsTo(City::class);
    }
}
