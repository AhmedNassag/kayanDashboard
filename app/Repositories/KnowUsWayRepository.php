<?php

namespace App\Repositories;

use App\Models\KnowUsWay;

class KnowUsWayRepository
{
    public function store($knowUsWay)
    {
        return KnowUsWay::create($knowUsWay);
    }

    public function update($knowUsWayInput)
    {
        $knowUsWay = KnowUsWay::find($knowUsWayInput["id"]);
        $knowUsWay->name = $knowUsWayInput["name"];
        $knowUsWay->save();
        return $knowUsWay;
    }

    public function delete($id)
    {
        $knowUsWay = KnowUsWay::find($id);
        if ($knowUsWay && $knowUsWay->clients) {
            $knowUsWay->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return KnowUsWay::where("name", "like", "%$text%")->paginate($pageSize);
    }

}
