<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ["id"];

    protected $appends=['text'];
    
    public function getTextAttribute()
    {
        return $this->user->name;
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function city()
    {
        return $this->belongsTo(City::class);
    }
    function area()
    {
        return $this->belongsTo(Area::class);
    }
}
