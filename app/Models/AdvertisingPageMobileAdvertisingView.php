<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPageMobileAdvertisingView extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'advertising_page_mobile_advertising_views';


    //  start raletion
    public function pageMobile()
    {
        return $this->belongsTo(AdvertisingPageMobile::class,'page_mobile_id');
    }

    public function view()
    {
        return $this->belongsTo(AdvertisingView::class,'view_id');
    }

    public function size()
    {
        return $this->hasOne(AdvertisingSizeMobile::class,'page_mobile_view_id','id');
    }

    public function packages()
    {
        return $this->belongsToMany(AdvertisingPackage::class,'page_view_packages','page_view_mobile_id','package_id')->withTimestamps();
    }

    public function schedule()
    {
        return $this->belongsToMany(AdvertiseSchedule::class,'schedule_page_mobiles','page_mobile_id','schedule_id')
            ->withTimestamps();
    }

}
