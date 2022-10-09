<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->nameAr;
    }

    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function alternativeDetails()
    {
        return $this->hasMany(AlternativeDetail::class);
    }
}
