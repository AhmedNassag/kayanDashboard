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

        $homePage->views()->attach([1,2,3,4,5,6,7,8,9,10,11]);

        AdvertisingPageAdvertisingView::find(1)->size()->create
        ([
            "width" => 1450,
            "height" => 307,
        ]);
        AdvertisingPageAdvertisingView::find(2)->size()->create
        ([
            "width" => 1450,
            "height" => 307,
        ]);
        AdvertisingPageAdvertisingView::find(3)->size()->create
        ([
            "width" => 1450,
            "height" => 307,
        ]);
        AdvertisingPageAdvertisingView::find(4)->size()->create
        ([
            "width" => 1450,
            "height" => 307,
        ]);
        AdvertisingPageAdvertisingView::find(5)->size()->create
        ([
            "width" => 250,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(6)->size()->create
        ([
            "width" => 250,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(7)->size()->create
        ([
            "width" => 250,
            "height" => 250,
        ]);
        AdvertisingPageAdvertisingView::find(8)->size()->create
        ([
            "width" => 1450,
            "height" => 200,
        ]);
        AdvertisingPageAdvertisingView::find(9)->size()->create
        ([
            "width" => 1450,
            "height" => 200,
        ]);
        AdvertisingPageAdvertisingView::find(10)->size()->create
        ([
            "width" => 1450,
            "height" => 200,
        ]);
        AdvertisingPageAdvertisingView::find(11)->size()->create
        ([
            "width" => 1400,
            "height" => 2500,
        ]);





        $productsPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة المنتجات',
        ]);

        $productsPage->views()->attach([12,13,14]);

        AdvertisingPageAdvertisingView::find(12)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(13)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(14)->size()->create
        ([
            "width" => 1000,
            "height" => 200,
        ]);





        $detailsPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة تفاصيل المنتجات',
        ]);

        $detailsPage->views()->attach([12, 13, 14]);

        AdvertisingPageAdvertisingView::find(15)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(16)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(17)->size()->create
        ([
            "width" => 1000,
            "height" => 200,
        ]);





        $alternativesPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة بدائل المنتجات',
        ]);

        $alternativesPage->views()->attach([12, 13, 14]);

        AdvertisingPageAdvertisingView::find(18)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(19)->size()->create
        ([
            "width" => 250,
            "height" => 400,
        ]);
        AdvertisingPageAdvertisingView::find(20)->size()->create
        ([
            "width" => 1000,
            "height" => 200,
        ]);





        $shoppingPage =  AdvertisingPage::create
        ([
            "name" => 'صفحة السلة',
        ]);

        $shoppingPage->views()->attach([14]);

        AdvertisingPageAdvertisingView::find(21)->size()->create
        ([
            "width" => 1000,
            "height" => 200,
        ]);





        $profilePage =  AdvertisingPage::create
        ([
            "name" => 'صفحة بروفايل العميل',
        ]);

        $profilePage->views()->attach([14, 15]);

        AdvertisingPageAdvertisingView::find(22)->size()->create
        ([
            "width" => 1000,
            "height" => 200,
        ]);
        AdvertisingPageAdvertisingView::find(23)->size()->create([
            "width" => 1000,
            "height" => 200,
        ]);



    }
}
