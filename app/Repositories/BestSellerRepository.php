<?php

namespace App\Repositories;

use App\Models\BestSeller;
use App\Models\BestSellerProduct;
use App\Models\Product;
use App\Models\Supplier;

class BestSellerRepository
{
    //Deal settings
    public function insertBestSellerSettings($bestSellerInput)
    {
        $bestSeller = BestSeller::first();
        $bestSeller ? $this->updateBestSellerSettings($bestSeller, $bestSellerInput)
            : BestSeller::create($bestSellerInput);
    }

    public function getBestSellerSettings()
    {
        return BestSeller::first();
    }

    //Deal Prices
    public function store($input)
    {
        $ourSupplier = $this->getOurSupplier();
        $bestSellerSettings = BestSeller::first();
        $input["supplier_id"] = $ourSupplier ? $ourSupplier->id : null;
        $input["best_seller_id"] = $bestSellerSettings ? $bestSellerSettings->id : null;
        return BestSellerProduct::create($input);
    }
    public function update($input)
    {
        BestSellerProduct::where("id", $input["id"])->update($input);
        return $input;
    }
    public function getPage($pageSize, $text)
    {
        return BestSellerProduct::with("product")->whereHas("product", function ($query) use ($text) {
            $query->where("nameAr", "like", "%$text%")->orWhere("nameEn", "like", "%$text%");
        })->paginate($pageSize);
    }
    public function delete($id)
    {
        BestSellerProduct::destroy($id);
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
    public function updateBestSellerSettings(&$bestSeller, $bestSellerInput)
    {
        $bestSeller->limit = $bestSellerInput["limit"];
        $bestSeller->save();
    }
}
