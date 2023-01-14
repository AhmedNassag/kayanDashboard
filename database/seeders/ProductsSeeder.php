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
        $sheet = ExcelSheetProduct::where('id','!=',1)->get();
        foreach($sheet->groupBy('Company') as $key => $company){
            Company::create(['name_en' => $key,"name_ar" => $key]);
        }
        foreach($sheet->groupBy('Sub-Division') as $cate_name =>  $category){
            $category_model = Category::create(['name' => $cate_name]);
            foreach(collect($category)->groupBy('Product Category')->toArray() as $subName => $sub_category){
                $sub_category_model = SubCategory::create([
                    'name' => $subName,
                    'category_id' => $category_model->id,
                ]);
                foreach($sub_category as $product){
                    $company = Company::where('name_en','like',"%".$product['Company']."%")->first();
                    if($company){
                        Product::create([
                            "product_code" => $product['Code'],
                            "nameAr" => $product['Description AR'],
                            "nameEn" => $product['Description'],
                            "company_id" => $company->id,
                            "barcode" => uniqid($product['Code']),
                            "category_id" => $category_model->id,
                            "sub_category_id" => $sub_category_model->id,
                        ]);
                    }

                }

            }
        }


    }
}
