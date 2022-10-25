<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPage extends Model
{
    use HasFactory;

    public $translatedAttributes = ['name'];

    protected $translationForeignKey = 'page_id';

    protected $guarded = ['id'];
    protected $hidden = ['translations'];


    // start Relation

    public function views()
    {
        return $this->belongsToMany(AdvertisingView::class,'advertising_page_advertising_views','page_id','view_id')->withPivot('id')->withTimestamps();
    }

}
