<?php

namespace App\Imports;

use App\Models\ExcelSheetProduct;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelSheetProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ExcelSheetProduct::create([
            'Code' => $row[0],
            'Description' => $row[1],
            'Description AR' => $row[2],
            'Sales Price' => $row[3],
            'Commercial Name EN' => $row[4],
            'Commercial Name AR' => $row[5],
            'Product Division' => $row[6],
            'Sub-Division' => $row[7],
            'Product Category' => $row[8],
            'Company' => $row[9],
        ]);
    }
}
