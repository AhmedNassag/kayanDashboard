<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = ["shippings_ids"];
    public function shippings()
    {
        return $this->belongsToMany(Shipping::class);
    }

    //start relation
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function refuseds()
    {
        return $this->hasMany(Refused::class);
    }
}
