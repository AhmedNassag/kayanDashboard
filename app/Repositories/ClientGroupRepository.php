<?php

namespace App\Repositories;

use App\Models\ClientGroup;

class ClientGroupRepository
{
    public function store($ClientGroup)
    {
        return ClientGroup::create($ClientGroup);
    }

    public function update($ClientGroupInput)
    {
        $ClientGroup = ClientGroup::find($ClientGroupInput["id"]);
        $ClientGroup->name = $ClientGroupInput["name"];
        $ClientGroup->save();
        return $ClientGroup;
    }

    public function delete($id)
    {
        $ClientGroup = ClientGroup::find($id);
        if ($ClientGroup) {
            $ClientGroup->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return ClientGroup::where("name", "like", "%$text%")->paginate($pageSize);
    }
}
