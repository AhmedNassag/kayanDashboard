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

    public function getPage($pageSize = 10, $text)
    {
        $city_query =  City::where("name", "like", "%$text%")
        ->withCount(['orders as number_of_orders' =>function($q) {
            $q->whereIn('order_status' , ['Pending','Shipping','Processing','Completed']);
        }]);
        if(request()->city_filter){
            $city_query->orderBy('number_of_orders',request()->city_filter == 'least_ordered' ? 'asc' :'desc');
        }else{
            $city_query->latest();
        }
        return  $city_query->paginate($pageSize);

    }
}
