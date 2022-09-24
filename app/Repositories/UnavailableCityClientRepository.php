<?php

namespace App\Repositories;

use App\Models\Client;

class UnavailableCityClientRepository
{
    public function getUnavailableCitiesClients($pageSize, $text)
    {
        return Client::with("user", "city")
            ->WhereRelation("city", "available", 0)
            ->where(function ($query) use ($text) {
                $query->whereHas("user", function ($query) use ($text) {
                    $query->where("name", "like", "%$text%")
                        ->orWhere("email", $text)
                        ->orWhere("phone", $text);
                })->orWhereRelation("city", "name", "like", "%$text%");
            })
            ->paginate($pageSize);
    }

    public function getAllUnavailableCitiesClients()
    {
        return Client::with(["user", "city"])
            ->WhereRelation("city", "available", 0)
            ->get();
    }
}
