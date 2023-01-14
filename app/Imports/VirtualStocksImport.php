<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
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
        if($row && $row[0] && $row[1] && $row[2] && $row[3] && $row[4]){
            $product = Product::where('product_code',$row[4])->first();
            if($product)
            return Price::create([
                'quantity'        => intval($row[0]),
                'pharmacyPrice'   => floatval($row[1]) - ( floatval($row[1]) * (floatval($row[2]) / 100) ),
                'publicPrice'     => floatval($row[1]),
                'clientDiscount'  => floatval($row[2]),
                'kayanDiscount'   => floatval($row[3]),
                'kayanProfit'     => floatval($row[3]) - floatval($row[2]),
                'product_id'      => $product->id,
                'supplier_id'     => request()->supplier_id,
            ]);
        }

    }
}
