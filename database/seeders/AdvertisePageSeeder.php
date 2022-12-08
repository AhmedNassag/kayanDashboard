<?php

namespace Database\Seeders;

use App\Models\AdvertisingPage;
use App\Models\AdvertisingPageAdvertisingView;
use Illuminate\Database\Seeder;

class AdvertisePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        $homePage = AdvertisingPage::create
        ([
            "name" => 'الصفحة الرئيسية'
        ]);

        $homePage->views()->attach([1,2,3,4,5,6,7]);


        AdvertisingPageAdvertisingView::find(1)->size()->create
        ([
            "width" => 300,
            "height" => 300,
        ]);
        AdvertisingPageAdvertisingView::find(2)->size()->create
        ([
            "width" => 300,
            "height" => 300,
        ]);
        AdvertisingPageAdvertisingView::find(3)->size()->create
        ([
            "width" => 300,
            "height" => 300,
        ]);
        AdvertisingPageAdvertisingView::find(4)->size()->create
        ([
            "width" => 1550,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(5)->size()->create
        ([
            "width" => 1550,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(6)->size()->create
        ([
            "width" => 1550,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(7)->size()->create
        ([
            "width" => 1000,
            "height" => 600,
        ]);





        $productsPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة المنتجات',
        ]);

        $productsPage->views()->attach([8,9,10]);

        AdvertisingPageAdvertisingView::find(8)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(9)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(10)->size()->create
        ([
            "width" => 1000,
            "height" => 250,
        ]);





        $detailsPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة تفاصيل المنتجات',
        ]);

        $detailsPage->views()->attach([11, 12, 13]);

        AdvertisingPageAdvertisingView::find(11)->size()->create
        ([
            "width" => 600,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(12)->size()->create
        ([
            "width" => 600,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(13)->size()->create
        ([
            "width" => 1240,
            "height" => 250,
        ]);


        $shoppingPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة السلة',
        ]);

        $shoppingPage->views()->attach([14]);

        AdvertisingPageAdvertisingView::find(14)->size()->create
        ([
            "width" => 1240,
            "height" => 250,
        ]);



        $profilePage =  AdvertisingPage::create
        ([
            "name" => 'صفحة بروفايل العميل',
        ]);

        $profilePage->views()->attach([15, 16]);

        AdvertisingPageAdvertisingView::find(15)->size()->create
        ([
            "width" => 1000,
            "height" => 200,
        ]);
        AdvertisingPageAdvertisingView::find(16)->size()->create([
            "width" => 1000,
            "height" => 200,
        ]);



    }
}
