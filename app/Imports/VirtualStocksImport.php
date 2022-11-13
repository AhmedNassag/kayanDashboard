<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;

class VirtualStocksImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return Price::create([
            'quantity'        => intval($row[0]),
            'pharmacyPrice'   => floatval($row[2]) - ( floatval($row[2]) * (floatval($row[3]) / 100) ),
            'publicPrice'     => floatval($row[2]),
            'clientDiscount'  => floatval($row[3]),
            'kayanDiscount'   => floatval($row[4]),
            'kayanProfit'     => floatval($row[4]) - floatval($row[3]),
            'product_id'      => intval($row[6]),
            'supplier_id'     => intval($row[7]),
        ]);
    }
}
