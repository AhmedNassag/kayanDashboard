<?php

namespace Database\Seeders;

use App\Imports\ExcelSheetProductsImport;
use App\Models\Category;
use App\Models\Company;
use App\Models\ExcelSheetProduct;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::create(['name' => 'ادوية']);
        Category::create(['name' => 'مستلزمات']);
        Category::create(['name' => 'مستحضرات']);
        SubCategory::create(['name' => 'ادوية' ,'category_id' => 1]);
        SubCategory::create(['name' => 'مستلزمات' ,'category_id' => 2]);
        SubCategory::create(['name' => 'مستحضرات' ,'category_id' => 3]);

        $sheet = ExcelSheetProduct::where('id','!=',1)->get();
        foreach($sheet->groupBy('Company') as $key => $company){
            Company::create(['name_en' => $key,"name_ar" => $key]);
        }
        foreach($sheet as $product){
            $company = Company::where('name_en','like',"%".$product['Company']."%")->first();
            if($company){
                $cat_and_sub_cat = $this->getCategoryAndSubCategoryId($product['Code']);
                Product::create([
                    "product_code" => $product['Code'],
                    "image" => $product['Code'].".jpg",
                    "nameAr" => $product['Description AR'],
                    "nameEn" => $product['Description'],
                    "company_id" => $company->id,
                    "barcode" => $product['Code'],
                    "category_id" => $cat_and_sub_cat,
                    "sub_category_id" => $cat_and_sub_cat,
                ]);
            }
        }
    }

    private function getCategoryAndSubCategoryId($product_code)
    {
        if(strpos($product_code,'ED-')) // for category 2
            return 2;
        elseif(strpos($product_code,'OS-')) //for cosmatics
            return 3;
        else //for medical
            return 1;

    }

}


