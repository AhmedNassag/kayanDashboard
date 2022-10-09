<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository
{
    public function store($supplierInput)
    {
        return Supplier::create($supplierInput);
    }

    public function update($supplierInput)
    {
        $supplier = Supplier::find($supplierInput["id"]);
        $supplier->name = $supplierInput["name"];
        $supplier->address = $supplierInput["address"];
        $supplier->phone = $supplierInput["phone"];
        $supplier->type = $supplierInput["type"];
        $supplier->commerical_register = $supplierInput["commerical_register"] ?? null;
        $supplier->tax_card = $supplierInput["tax_card"] ?? null;
        $supplier->responsible_name = $supplierInput["responsible_name"] ?? null;
        $supplier->responsible_phone = $supplierInput["responsible_phone"] ?? null;
        $supplier->save();
        return $supplier;
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return Supplier::where("name", "like", "%$text%")
            ->orWhere("address", "like", "%$text%")
            ->orWhere("phone", $text)
            ->paginate($pageSize);
    }
    public function toggleActivation($id)
    {
        $supplier = Supplier::find($id);
        $supplier->active = !$supplier->active;
        $supplier->save();
    }
}
