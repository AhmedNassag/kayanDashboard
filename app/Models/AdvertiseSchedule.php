<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertiseSchedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'advertise_schedules';

    //start raletions

    public function packages()
    {
        return $this->belongsTo(AdvertisingPackage::class,'advertising_package_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function page()
    {
        return $this->belongsToMany(AdvertisingPageAdvertisingView::class,'schedule_pages','schedule_id','page_id')
            ->withTimestamps();
    }

    public function pageMobile()
    {
        return $this->belongsToMany(AdvertisingPageMobileAdvertisingView::class,'schedule_page_mobiles','schedule_id','page_mobile_id')
            ->withTimestamps();
    }
}
