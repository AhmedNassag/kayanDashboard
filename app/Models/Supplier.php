<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

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
