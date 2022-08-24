<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ["id"];
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
