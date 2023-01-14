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

    public function getPage($pageSize, $text,$city)
    {
        return Area::with("city")
        ->where(function($q) use($text){
            $q->when($text , function ($q) use($text){
                $q->where("name", "like", "%$text%")
                ->orWhere("shipping_price", "like", "%$text%");
            });
        })->whereRelation('city',"name", "like", "%$city%")
        ->paginate($pageSize);
    }
    public function getCities()
    {
        return City::get();
    }
}
