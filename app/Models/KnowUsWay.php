<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowUsWay extends Model
{
    protected $guarded = [];

    public function clients()
    {
        return $this->hasMany(Client::class,'know_us_way_id');
    }
}
