<?php

namespace App\Repositories;

use App\Models\Unit;

class UnitRepository
{
    public function store($unit)
    {
        return Unit::create($unit);
    }

    public function update($unitInput)
    {
        $unit = Unit::find($unitInput["id"]);
        $unit->name = $unitInput["name"];
        $unit->save();
        return $unit;
    }

    public function delete($id)
    {
        $unit = unit::find($id);
        if ($unit) {
            $unit->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return unit::where("name", "like", "%$text%")->paginate($pageSize);
    }
    
}
