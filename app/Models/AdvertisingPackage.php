<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AdvertisingPackage extends Model
{
    use HasFactory;

    protected $appends = ['text'];
    public function getTextAttribute()
    {
        return $this->name;
    }

    // public $translatedAttributes = ['name'];

    protected $translationForeignKey = 'package_id';

    protected $guarded = ['id'];
    // protected $hidden = ['translations'];

    protected $table = 'advertising_packages';


    //  start raletion

    public function page_view()
    {
        return $this->belongsToMany(AdvertisingPageAdvertisingView::class,'page_view_packages','package_id','page_view_id')->withTimestamps()->withPivot('id');
    }

    public function page_view_mobile()
    {
        return $this->belongsToMany(AdvertisingPageMobileAdvertisingView::class,'page_view_mobile_packages','package_id','page_view_mobile_id')->withTimestamps();
    }

}
