<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }
}
