<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];

    protected $appends=['text'];

    public function getTextAttribute()
    {
        return $this->name;
    }
    
    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    //start relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
