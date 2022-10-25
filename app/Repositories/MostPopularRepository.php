<?php

namespace App\Repositories;

use App\Models\MostPopular;
use App\Models\MostPopularProduct;
use App\Models\Product;
use App\Models\Supplier;

class MostPopularRepository
{
    //Most popular settings
    public function insertMostPopularSettings($mostPopularInput)
    {
        $mostPopular = MostPopular::first();
        $mostPopular ? $this->updateMostPopularSettings($mostPopular, $mostPopularInput)
            : MostPopular::create($mostPopularInput);
    }

    public function getMostPopularSettings()
    {
        return MostPopular::first();
    }

    //Deal Prices
    public function store($input)
    {
        $ourSupplier = $this->getOurSupplier();
        $mostPopularSettings = MostPopular::first();
        $input["supplier_id"] = $ourSupplier ? $ourSupplier->id : null;
        $input["most_popular_id"] = $mostPopularSettings ? $mostPopularSettings->id : null;
        return MostPopularProduct::create($input);
    }
    public function update($input)
    {
        MostPopularProduct::where("id", $input["id"])->update($input);
        return $input;
    }
    public function getPage($pageSize, $text)
    {
        return MostPopularProduct::with("product")->whereHas("product", function ($query) use ($text) {
            $query->where("nameAr", "like", "%$text%")->orWhere("nameEn", "like", "%$text%");
        })->paginate($pageSize);
    }
    public function delete($id)
    {
        MostPopularProduct::destroy($id);
    }
    public function getProducts()
    {
        return Product::whereRelation("prices.supplier","is_our_supplier", 1)->get();
    }
    public function getOurSupplier()
    {
        return Supplier::where("is_our_supplier", 1)->first();
    }
    //Commons
    public function updateMostPopularSettings(&$mostPopular, $mostPopularInput)
    {
        $mostPopular->limit = $mostPopularInput["limit"];
        $mostPopular->save();
    }
}
