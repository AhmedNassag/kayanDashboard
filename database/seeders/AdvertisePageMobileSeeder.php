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
        $homePage = AdvertisingPageMobile::create
        ([
            "name" => 'الصفحة الرئيسية'
        ]);

        $homePage->views()->attach([1,2,3,4,5,6,7]);

        AdvertisingPageMobileAdvertisingView::find(1)->size()->create([
            "width" => 300,
            "height" => 300,
        ]);
        AdvertisingPageMobileAdvertisingView::find(2)->size()->create([
            "width" => 300,
            "height" => 300,
        ]);
        AdvertisingPageMobileAdvertisingView::find(3)->size()->create([
            "width" => 300,
            "height" => 300,
        ]);
        AdvertisingPageMobileAdvertisingView::find(4)->size()->create([
            "width" => 1000,
            "height" => 200,
        ]);
        AdvertisingPageMobileAdvertisingView::find(5)->size()->create([
            "width" => 1000,
            "height" => 200,
        ]);
        AdvertisingPageMobileAdvertisingView::find(6)->size()->create([
            "width" => 1000,
            "height" => 200,
        ]);
        AdvertisingPageMobileAdvertisingView::find(7)->size()->create([
            "width" => 400,
            "height" => 600,
        ]);

    }
}
