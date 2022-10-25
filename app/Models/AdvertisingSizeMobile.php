<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingSizeMobile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    //  start raletion
    //
    public function sizeMobile()
    {
        return $this->belongsTo(AdvertisingPageMobileAdvertisingView::class,'page_mobile_view_id','id');
    }

}
