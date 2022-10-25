<?php

namespace App\Repositories;

use App\Models\AlsoBought;
use App\Models\AlsoBoughtProduct;
use App\Models\Product;
use App\Models\Supplier;

class AlsoBoughtRepository
{
    //Also bought general settings
    public function insertAlsoBoughtSettings($alsoBoughtInput)
    {
        $alsoBought = AlsoBought::first();
        $alsoBought ? $this->updateAlsoBoughtSettings($alsoBought, $alsoBoughtInput)
            : AlsoBought::create($alsoBoughtInput);
    }

    public function getAlsoBoughtSettings()
    {
        return AlsoBought::first();
    }

    //Also bought products
    public function store($input)
    {
        $ourSupplier = $this->getOurSupplier();
        $alsoBoughtSettings = AlsoBought::first();
        $input["supplier_id"] = $ourSupplier ? $ourSupplier->id : null;
        $input["also_bought_id"] = $alsoBoughtSettings ? $alsoBoughtSettings->id : null;
        return AlsoBoughtProduct::create($input);
    }
    public function update($input)
    {
        AlsoBoughtProduct::where("id", $input["id"])->update($input);
        return $input;
    }
    public function getPage($pageSize, $text)
    {
        return AlsoBoughtProduct::with("product")->whereHas("product", function ($query) use ($text) {
            $query->where("nameAr", "like", "%$text%")->orWhere("nameEn", "like", "%$text%");
        })->paginate($pageSize);
    }
    public function delete($id)
    {
        AlsoBoughtProduct::destroy($id);
    }
    public function getProducts()
    {
        return Product::whereRelation("prices.supplier", "is_our_supplier", 1)->get();
    }
    public function getOurSupplier()
    {
        return Supplier::where("is_our_supplier", 1)->first();
    }
    //Commons
    public function updateAlsoBoughtSettings(&$alsoBought, $alsoBoughtInput)
    {
        $alsoBought->limit = $alsoBoughtInput["limit"];
        $alsoBought->save();
    }
}
