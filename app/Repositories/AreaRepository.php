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
        $area->available = $areaInput["available"];
        $area->city_id = $areaInput["city_id"];
        $area->save();
        $area["city"] = ["name" => $areaInput["city_name"]];
        return $area;
    }

    public function getPage($pageSize, $text)
    {
        return Area::with("city")->where("name", "like", "%$text%")->paginate($pageSize);
    }
    public function getCities()
    {
        return City::get();
    }
}
