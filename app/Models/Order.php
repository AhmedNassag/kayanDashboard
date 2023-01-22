<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends=['area_name','city_name'];

    public function orderTax()
    {
        return $this->hasMany(OrderTax::class,'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function representative(){
        return $this->belongsTo(User::class,'representative_id');
    }

    public function products()
    {
        return $this->hasMany(CartItem::class,'order_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d (H:i)');
    }
    public function getCityNameAttribute($value)
    {
        return $this->city?$this->city->name : '';
    }
    public function getAreaNameAttribute($value)
    {
        return $this->area?$this->area->name : '';
    }

    public function scopeGetCompletedOrdersThroughMonthGroubByWeeks($q,$year,$month){
        return $q->where('order_status','Completed')
        ->whereYear('created_at',$year)->whereMonth('created_at',$month)
        ->orderBy('created_at')->get()
        ->groupBy(function($data) {
            //week
            $date = explode(" ",$data->created_at);// get the date from this format 'Y/m/d (H:i)'
            return $this->getNameOfWeekInMounth(Carbon::parse($date[0])->format('d')); //groub by week

        });
    }

    public function getNameOfWeekInMounth($week_number)
    {
        switch(true){
            case ($week_number <= 7):
                $week_name = 'First Week';
                break;
            case ($week_number <= 15):
                $week_name = 'Second Week';
                break;
            case ($week_number <= 22):
                $week_name = 'Third Week';
                break;
            case ($week_number <= 29):
                $week_name = 'Fourth Week';
                break;
            case ($week_number <= 31):
                $week_name = 'Fifth Week';
                break;
        }
        return $week_name;
    }

}
