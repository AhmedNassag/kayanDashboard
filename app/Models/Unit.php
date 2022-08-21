<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];
}
