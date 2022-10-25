<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulePageMobile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'schedule_page_mobiles';

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }

    public function ManyMedia()
    {
        return $this->morphMany(Media::class,'mediable');
    }
}
