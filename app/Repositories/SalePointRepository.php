<?php

namespace App\Repositories;

use App\Commons\Consts\RelatedWith;
use App\Models\Category;
use App\Models\ClientGroup;
use App\Models\SalePoint;

class SalePointRepository
{

    public function store($salePointInput)
    {
        $salePoint = SalePoint::create($salePointInput);
        if ($salePointInput["related_with"] == RelatedWith::CLIENT_GROUP) {
            $salePoint->clients_groups()->sync($salePointInput["clients_groups_ids"]);
            $salePoint->clients_groups = $salePointInput["clients_groups_ids"];
        }
        return $salePoint;
    }

    public function update($salePointInput)
    {
        $salePoint = SalePoint::find($salePointInput["id"]);
        $salePoint->name = $salePointInput["name"];
        $salePoint->counter = $salePointInput["counter"];
        $salePoint->price = $salePointInput["price"];
        $salePoint->related_with = $salePointInput["related_with"];
        $salePoint->sub_category_id = $salePointInput["sub_category_id"] ?? null;
        $salePoint->product_id = $salePointInput["product_id"] ?? null;
        $salePoint->category_id = $salePointInput["category_id"] ?? null;
        $salePoint->save();
        $salePointInput["clients_groups_ids"] = $salePointInput["clients_groups_ids"] ?? [];
        $salePoint->clients_groups()->sync($salePointInput["clients_groups_ids"]);
        $salePoint->clients_groups = $salePointInput["clients_groups_ids"];
        return $salePoint;
    }

    public function getPage($pageSize, $text)
    {
        return SalePoint::where("name", "like", "%$text%")
            ->with("clients_groups")
            ->paginate($pageSize);
    }

    public function toggleActivation($id)
    {
        $salePoint = SalePoint::find($id);
        $salePoint->active = !$salePoint->active;
        $salePoint->save();
    }
    public function getMainCategories()
    {
        return Category::where("status", 1)->with(["subCategories" => function ($q) {
            $q->where("status", 1)->with(["products" => function ($q) {
                $q->where("status", 1);
            }]);
        }])->get();
    }
    public function getClientGroups()
    {
        return ClientGroup::get();
    }
}
