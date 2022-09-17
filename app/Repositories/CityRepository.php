<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    public function store($city)
    {
        return City::create($city);
    }

    public function update($cityInput)
    {
        $city = City::find($cityInput["id"]);
        $city->name = $cityInput["name"];
        $city->available = $cityInput["available"];
        $city->save();
        return $city;
    }

    public function getPage($pageSize, $text)
    {
        return City::where("name", "like", "%$text%")->paginate($pageSize);
    }
}
