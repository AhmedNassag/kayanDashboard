<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];


    //start relations
    function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    function responser()
    {
        return $this->belongsTo(User::class,'responser_id');
    }
}
