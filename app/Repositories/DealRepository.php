<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\DealPrice;
use App\Models\Product;
use App\Models\Supplier;
use Symfony\Component\Console\Input\Input;

class DealRepository
{
    //Deal settings
    public function insertDealSettings($dealInput)
    {
        $deal = Deal::first();
        $deal ? $this->updateDealSettings($deal, $dealInput) : Deal::create($dealInput);
    }

    public function getDealSettings()
    {
        return Deal::first();
    }

    //Deal Prices
    public function store($input)
    {
        $ourSupplier = $this->getOurSupplier();
        $dealSettings = Deal::first();
        $input["supplier_id"] = $ourSupplier ? $ourSupplier->id : null;
        $input["deal_id"] = $dealSettings ? $dealSettings->id : null;
        $input["pharmacyPrice"] = $this->getPharmacyPrice($input["publicPrice"], $input["clientDiscount"]);
        return DealPrice::create($input);
    }
    public function update($input)
    {
        $input["pharmacyPrice"] = $this->getPharmacyPrice($input["publicPrice"], $input["clientDiscount"]);
        DealPrice::where("id", $input["id"])->update($input);
        return $input;
    }
    public function getPage($pageSize, $text)
    {
        return DealPrice::with("product")->whereHas("product", function ($query) use ($text) {
            $query->where("nameAr", "like", "%$text%")
                ->orWhere("nameEn", "like", "%$text%");
        })
            ->orWhere("publicPrice", $text)
            ->orWhere("clientDiscount", $text)
            ->orWhere("pharmacyPrice", $text)->paginate($pageSize);
    }
    public function delete($id)
    {
        DealPrice::destroy($id);
    }
    public function getProducts()
    {
        return Product::with(["prices" => function ($query) {
            $query->whereRelation("supplier", "is_our_supplier", 1);
        }])->get();
    }
    public function getOurSupplier()
    {
        return Supplier::where("is_our_supplier", 1)->first();
    }
    //Commons
    public function updateDealSettings(&$deal, $dealInput)
    {
        $deal->end_at = $dealInput["end_at"];
        $deal->limit = $dealInput["limit"];
        $deal->save();
    }
    public function getPharmacyPrice($publicPrice, $clientDiscount)
    {
        return ceil($publicPrice - $publicPrice * ($clientDiscount / 100.00));
    }
}
