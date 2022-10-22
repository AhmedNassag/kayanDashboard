<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdOwner extends Model
{
    use HasFactory;

    protected $table = 'ad_owners';
    protected $guarded = [];

    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
