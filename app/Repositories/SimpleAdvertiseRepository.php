<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\SimpleAdvertise;

class SimpleAdvertiseRepository
{
    public function store($simpleAdvertiseInput)
    {
        $simpleAdvertise = SimpleAdvertise::create($simpleAdvertiseInput);
        $simpleAdvertise->product = $this->getProductName($simpleAdvertiseInput);
        return $simpleAdvertise;
    }

    public function update($simpleAdvertiseInput)
    {
        $simpleAdvertise = SimpleAdvertise::find($simpleAdvertiseInput["id"]);
        $oldImage = $simpleAdvertise->image;
        $simpleAdvertise->title = $simpleAdvertiseInput["title"];
        $simpleAdvertise->url = $simpleAdvertiseInput["url"] ?? null;
        $simpleAdvertise->product_id = $simpleAdvertiseInput["product_id"] ?? null;
        $simpleAdvertise->external = $simpleAdvertiseInput["external"] ?? null;
        $simpleAdvertise->image = isset($simpleAdvertiseInput["image"]) ? $simpleAdvertiseInput["image"] : $oldImage;
        $simpleAdvertise->save();
        $simpleAdvertise->product = $this->getProductName($simpleAdvertiseInput);
        return ["old_image" => $oldImage, "updated_simple_advertise" => $simpleAdvertise];
    }

    public function delete($id)
    {
        $simpleAdvertise = SimpleAdvertise::find($id);
        $oldImage = null;
        if ($simpleAdvertise) {
            $oldImage = $simpleAdvertise->image;
            $simpleAdvertise->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return SimpleAdvertise::where("title", "like", "%$text%")->with("product")
            ->paginate($pageSize);
    }

    public function getProducts()
    {
        return Product::where("status", 1)->get();
    }
    //Commons
    private function getProductName($simpleAdvertiseInput)
    {
        return [
            "nameAr" => $simpleAdvertiseInput["product_name_ar"] ?? "",
            "nameEn" => $simpleAdvertiseInput["product_name_en"] ?? "",
        ];
    }
}
