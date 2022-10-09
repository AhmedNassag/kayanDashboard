<?php

namespace App\Repositories;

use App\Models\Shipping;

class ShippingRepository
{
    public function store($shipping)
    {
        return Shipping::create($shipping);
    }

    public function update($shippingInput)
    {
        $shipping = Shipping::find($shippingInput["id"]);
        $shipping->name = $shippingInput["name"];
        $shipping->description = $shippingInput["description"];
        $shipping->save();
        return $shipping;
    }

    public function delete($id)
    {
        $shipping = shipping::find($id);
        if ($shipping) {
            $shipping->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return shipping::where("name", "like", "%$text%")->paginate($pageSize);
    }

    public function toggleActivation($id)
    {
        $shipping = Shipping::find($id);
        $shipping->active = !$shipping->active;
        $shipping->save();
    }
}
