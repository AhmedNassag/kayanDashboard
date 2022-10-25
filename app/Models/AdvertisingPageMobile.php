<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPageMobile extends Model
{
    use HasFactory;

    public $translatedAttributes = ['name'];

    protected $translationForeignKey = 'page_id';

    protected $guarded = ['id'];
    protected $hidden = ['translations'];
    protected $table = 'advertising_page_mobiles';



    //  start Relation

    public function views()
    {
        return $this->belongsToMany(AdvertisingView::class,'advertising_page_mobile_advertising_views','page_mobile_id','view_id')->withPivot('id')->withTimestamps();
    }


}
