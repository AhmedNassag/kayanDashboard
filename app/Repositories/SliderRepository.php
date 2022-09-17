<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Slider;

class SliderRepository
{
    public function store($sliderInput)
    {
        $slider = Slider::create($sliderInput);
        $slider->product = $this->getProductName($sliderInput);
        return $slider;
    }

    public function update($sliderInput)
    {
        $slider = Slider::find($sliderInput["id"]);
        $oldImage = $slider->image;
        $slider->title = $sliderInput["title"];
        $slider->color = $sliderInput["color"];
        $slider->url = $sliderInput["url"] ?? null;
        $slider->product_id = $sliderInput["product_id"] ?? null;
        $slider->external = $sliderInput["external"] ?? null;
        $slider->image = isset($sliderInput["image"]) ? $sliderInput["image"] : $oldImage;
        $slider->save();
        $slider->product = $this->getProductName($sliderInput);
        return ["old_image" => $oldImage, "updated_slider" => $slider];
    }

    public function delete($id)
    {
        $slider = Slider::find($id);
        $oldImage = null;
        if ($slider) {
            $oldImage = $slider->image;
            $slider->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Slider::where("title", "like", "%$text%")->with("product.productName")
            ->paginate($pageSize);
    }

    public function getProducts()
    {
        return Product::with("productName")->where("status", 1)->get();
    }
    //Commons
    private function getProductName($sliderInput)
    {
        return ["product_name" => [
            "nameAr" => $sliderInput["product_name_ar"] ?? "",
            "nameEn" => $sliderInput["product_name_en"] ?? "",
        ]];
    }
}
