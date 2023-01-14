<?php

namespace Database\Seeders;

use App\Imports\ExcelSheetProductsImport;
use App\Models\AboutBanner;
use App\Models\FooterLink;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ExcelSheetProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $file = public_path('excel_products_sheet.xlsx');
       Excel::import(new ExcelSheetProductsImport, $file);
    }
}
