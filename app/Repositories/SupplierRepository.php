<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Supplier;

class SupplierRepository
{
    public function store($supplierInput)
    {
        $supplier = Supplier::create($supplierInput);
        $supplier->shippings()->sync($supplierInput["shippings_ids"]);
        $supplier->shippings = $supplierInput["shippings_ids"];
        return $supplier;
    }

    public function update($supplierInput)
    {
        $supplier = Supplier::find($supplierInput["id"]);
        $supplier->name = $supplierInput["name"];
        $supplier->address = $supplierInput["address"];
        $supplier->phone = $supplierInput["phone"];
        $supplier->commerical_register = $supplierInput["commerical_register"] ?? null;
        $supplier->tax_card = $supplierInput["tax_card"] ?? null;
        $supplier->responsible_name = $supplierInput["responsible_name"] ?? null;
        $supplier->responsible_phone = $supplierInput["responsible_phone"] ?? null;
        $supplier->payment_type = $supplierInput["payment_type"];
        $supplier->account_number = $supplierInput["account_number"] ?? null;
        $supplier->payment_phone = $supplierInput["payment_phone"] ?? null;
        $supplier->payment_responsible_name = $supplierInput["payment_responsible_name"] ?? null;
        $supplier->payment_responsible_phone = $supplierInput["payment_responsible_phone"] ?? null;
        $supplier->payment_responsible_card_number = $supplierInput["payment_responsible_card_number"] ?? null;
        $supplier->employee_id = $supplierInput["employee_id"] ?? null;
        $supplier->save();
        $supplier->shippings()->sync($supplierInput["shippings_ids"]);
        $supplier->shippings = $supplierInput["shippings_ids"];
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
        $suppliers = Supplier::where("name", "like", "%$text%")
            ->orWhere("address", "like", "%$text%")
            ->orWhere("phone", $text)
            ->with("shippings")
            ->paginate($pageSize);
        return $suppliers;
    }
    public function toggleActivation($id)
    {
        $supplier = Supplier::find($id);
        $supplier->active = !$supplier->active;
        $supplier->save();
    }
    public function getAllEmployees()
    {
        return Employee::whereHas("user", function ($q) {
            $q->where("status", 1);
        })->with("user")->get();
    }
}
