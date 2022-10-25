<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulePage extends Model
{
    use HasFactory;

    protected $appends = ['imagePath'];

    protected $guarded = ['id'];

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }

    public function ManyMedia()
    {
        return $this->morphMany(Media::class,'mediable');
    }

    public function getImagePathAttribute(): string
    {
        return asset('web/img/advertise/'.$this->media->file_name);;
    }

}
