<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
use App\Models\ProductLog;
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
        foreach(Price::where('supplier_id',request()->supplier_id)->get() as $price){
            $price->update(['quantity' => 0]);
            ProductLog::create([
                'diff_qty' => 0,
                'total_qty' => 0,
                'pharmacyPrice' => 0,
                'publicPrice' => 0,
                'clientDiscount' => 0,
                'kayanDiscount' => 0,
                'kayanProfit' => 0,
                'price_id' => $price->id,
            ]);
        }
        if ($row && $row[0] && $row[1] && $row[2] && $row[3] && $row[4]) {
            $product = Product::where('product_code', $row[4])->first();
            if ($product) {
                $pharmacyPrice = floatval($row[1]) - (floatval($row[1]) * (floatval($row[2]) / 100));
                $kayanProfit = floatval($row[3]) - floatval($row[2]);
                if ($price = Price::where('product_id', $product->id)->where('supplier_id', request()->supplier_id)->first()) {
                    // just update in case there are diffrence between data
                    if (
                        intval($row[0]) != $price->quantity
                        || $pharmacyPrice != $price->pharmacyPrice
                        || floatval($row[1]) != $price->publicPrice
                        || floatval($row[2]) != $price->clientDiscount
                        || floatval($row[3]) != $price->kayanDiscount
                        || $kayanProfit != $price->kayanProfit
                    ) {
                        $old_qty = $price->quantity; //befor update quantity
                        $price->update([
                            'quantity'        => intval($row[0]),
                            'pharmacyPrice'   => $pharmacyPrice,
                            'publicPrice'     => floatval($row[1]),
                            'clientDiscount'  => floatval($row[2]),
                            'kayanDiscount'   => floatval($row[3]),
                            'kayanProfit'     => $kayanProfit,
                        ]);

                        $this->update_supplier_price_and_store_in_product_logs(
                            $price->quantity - $old_qty,
                            $price->quantity,
                            $price->pharmacyPrice,
                            $price->publicPrice,
                            $price->clientDiscount,
                            $price->kayanDiscount,
                            $price->kayanProfit,
                            $price->id,
                            $old_qty,
                        );
                    }
                } else {
                    $price = Price::create([
                        'quantity'        => intval($row[0]),
                        'pharmacyPrice'   => $pharmacyPrice,
                        'publicPrice'     => floatval($row[1]),
                        'clientDiscount'  => floatval($row[2]),
                        'kayanDiscount'   => floatval($row[3]),
                        'kayanProfit'     => $kayanProfit,
                        'product_id'      => $product->id,
                        'supplier_id'     => request()->supplier_id,
                    ]);
                    $this->store_in_product_logs(0, intval($row[0]), $pharmacyPrice, floatval($row[1]), floatval($row[2]), floatval($row[3]), floatval($row[3]) - floatval($row[2]), $price->id);
                    return;
                }
            }
        }
    }

    public function store_in_product_logs($diff_qty, $total_qty, $pharmacyPrice, $publicPrice, $clientDiscount, $kayanDiscount, $kayanProfit, $price_id)
    {
        ProductLog::create([
            'diff_qty' => $diff_qty,
            'total_qty' => $total_qty,
            'pharmacyPrice' => $pharmacyPrice,
            'publicPrice' => $publicPrice,
            'clientDiscount' => $clientDiscount,
            'kayanDiscount' => $kayanDiscount,
            'kayanProfit' => $kayanProfit,
            'price_id' => $price_id,
        ]);
    }

    public function update_supplier_price_and_store_in_product_logs($diff_qty, $total_qty, $pharmacyPrice, $publicPrice, $clientDiscount, $kayanDiscount, $kayanProfit, $price_id, $old_qty)
    {
        $latest_log = ProductLog::where('price_id', $price_id)->latest()->first();
        $latest_log->update(['sold_quantity' => $latest_log->total_qty - $old_qty]);
        ProductLog::create([
            'diff_qty' => $diff_qty,
            'total_qty' => $total_qty,
            'pharmacyPrice' => $pharmacyPrice,
            'publicPrice' => $publicPrice,
            'clientDiscount' => $clientDiscount,
            'kayanDiscount' => $kayanDiscount,
            'kayanProfit' => $kayanProfit,
            'price_id' => $price_id,
        ]);
    }
}
