<?php

namespace App\Repositories;

use App\Models\Area;
use App\Models\City;

class AreaRepository
{
    public function store($areaInput)
    {
        $area = Area::create($areaInput);
        $area["city"] = ["name" => $areaInput["city_name"]];
        return $area;
    }

    public function update($areaInput)
    {
        $area = Area::find($areaInput["id"]);
        $area->name = $areaInput["name"];
        $area->city_id = $areaInput["city_id"];
        $area->shipping_price = $areaInput["shipping_price"];
        $area->save();
        $area["city"] = ["name" => $areaInput["city_name"]];
        return $area;
    }

    public function getPage($pageSize = 25, $text,$city)
    {
        $area_query = Area::with("city")
        ->withCount(['orders as number_of_orders' =>function($q) {
            $q->whereIn('order_status' , ['Pending','Shipping','Processing','Completed']);
        }])

        ->where(function($q) use($text){
            $q->when($text , function ($q) use($text){
                $q->where("name", "like", "%$text%")
                ->orWhere("shipping_price", "like", "%$text%");
            });
        })->whereRelation('city',"name", "like", "%$city%");

        if(request()->area_filter){
            $area_query->orderBy('number_of_orders',request()->area_filter == 'least_ordered' ? 'asc' :'desc');
        }else{
            $area_query->latest();
        }
        return $area_query->paginate(20);
    }
    public function getCities()
    {
        return City::get();
    }
}
