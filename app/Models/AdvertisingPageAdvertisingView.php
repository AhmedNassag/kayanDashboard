<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPageAdvertisingView extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'advertising_page_advertising_views';



    public function size()
    {
        return $this->hasOne(AdvertisingSize::class,'page_view_id','id');
    }

    public function Page()
    {
        return $this->belongsTo(AdvertisingPage::class,'page_id');
    }

    public function view()
    {
        return $this->belongsTo(AdvertisingView::class,'view_id');
    }

    public function packages()
    {
        return $this->belongsToMany(AdvertisingPackage::class,'page_view_packages','page_view_mobile_id','package_id')->withTimestamps();
    }

    public function schedule()
    {
        return $this->belongsToMany(AdvertiseSchedule::class,'schedule_pages','page_id','schedule_id')
            ->withTimestamps();
    }

}
