<?php

namespace App\Repositories;

use App\Models\ClientGroup;

class ClientGroupRepository
{
    public function store($clientGroupInput)
    {
        $clientGroup = ClientGroup::create($clientGroupInput);
        $clientGroup->clients()->sync($clientGroupInput["clients_ids"]);
        $clientGroup->clients = $clientGroupInput["clients_ids"];
        return $clientGroup;
    }

    public function update($clientGroupInput)
    {
        $clientGroup = ClientGroup::find($clientGroupInput["id"]);
        $clientGroup->name = $clientGroupInput["name"];
        $clientGroup->save();
        $clientGroup->clients()->sync($clientGroupInput["clients_ids"]);
        $clientGroup->clients = $clientGroupInput["clients_ids"];
        return $clientGroup;
    }

    public function getPage($pageSize, $text)
    {
        return ClientGroup::where("name", "like", "%$text%")->with("clients")
            ->paginate($pageSize);
    }
}
