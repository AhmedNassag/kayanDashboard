<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];
    protected $appends = ['text'];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
    public function getTextAttribute()
    {
        return $this->name;
    }
}
