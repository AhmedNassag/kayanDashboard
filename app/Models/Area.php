<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = ["city_name"];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
}
