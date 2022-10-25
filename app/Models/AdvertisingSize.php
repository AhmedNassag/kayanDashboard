<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingSize extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    public function page_view()
    {
        return $this->belongsTo(AdvertisingPageAdvertisingView::class,'page_view_id','id');
    }


}
