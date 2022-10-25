<?php

namespace Database\Seeders;

use App\Models\AdvertisingPageMobile;
use App\Models\AdvertisingPageMobileAdvertisingView;
use Illuminate\Database\Seeder;

class AdvertisePageMobileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $home = AdvertisingPageMobile::create([
            "name" => 'الصفحة الرئيسية'
        ]);

        $home->views()->attach([1,2,3]);

        AdvertisingPageMobileAdvertisingView::find(1)->size()->create([
            "width" => 500,
            "height" => 200,
        ]);

        AdvertisingPageMobileAdvertisingView::find(2)->size()->create([
            "width" => 500,
            "height" => 200,
        ]);

        AdvertisingPageMobileAdvertisingView::find(3)->size()->create([
            "width" => 500,
            "height" => 200,
        ]);

//        $branch = AdvertisingPageMobile::create([
//            "en" => ["name" => "branch page"],
//            "ar"=>["name" => 'الصفحه الفرعيه'],
//        ]);
//
//        $branch->views()->attach([1,2]);

//        $product = AdvertisingPageMobile::create([
//            "ar" => ["name" => "الصفحه المنتجات"],
//            "en"=>["name" => 'product page'],
//        ]);
//
//        $product->views()->attach([1]);
    }
}
