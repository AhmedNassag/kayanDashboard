<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingView extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $translatedAttributes = ['type'];

    protected $translationForeignKey = 'view_id';
    protected $hidden = ['translations'];

    public function pages()
    {
        return $this->belongsToMany(AdvertisingPage::class,'advertising_page_advertising_views','view_id','page_id')->withTimestamps();
    }

    public function page_mobile()
    {
        return $this->belongsToMany(AdvertisingPageMobile::class,'advertising_page_mobile_advertising_views','view_id','page_mobile_id')->withTimestamps();
    }

}
