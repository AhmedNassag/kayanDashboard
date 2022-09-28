<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\Product;

class DealRepository
{
    public function insertDeal($dealInput)
    {
        $this->deleteOldDeal();
        $this->insertNewDeal($dealInput);
    }
    public function getDeal()
    {
        return Deal::get();
    }
    public function getProducts()
    {
        return Product::with("productName")->where("status", 1)->get();
    }
    //Commons
    private function deleteOldDeal()
    {
        Deal::truncate();
    }
    private function insertNewDeal($dealInput)
    {
        foreach ($dealInput["products_with_discounts"] as $productWithDiscount) {
            $productWithDiscount["end_at"] = $dealInput["end_at"];
            Deal::create($productWithDiscount);
        }
    }
}
