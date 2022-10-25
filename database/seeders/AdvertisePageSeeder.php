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

        $home = AdvertisingPage::create([
            "name" => 'الصفحة الرئيسية'
        ]);

        $home->views()->attach([1,2,3]);

        AdvertisingPageAdvertisingView::find(1)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

        AdvertisingPageAdvertisingView::find(2)->size()->create([
            "width" => 450,
            "height" => 250,
        ]);

        AdvertisingPageAdvertisingView::find(3)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);



        $DesignersProject =  AdvertisingPage::create([
            "name" => 'صفحة مشاريع المصممين',
        ]);

        $DesignersProject->views()->attach([1]);

        AdvertisingPageAdvertisingView::find(4)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);



        $CompanysProject =  AdvertisingPage::create([
            "name" => 'صفحة مشاريع شركات التشطيبات',
        ]);

        $CompanysProject->views()->attach([1]);

        AdvertisingPageAdvertisingView::find(5)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);




        $designersProviders =  AdvertisingPage::create([
            "name" => 'صفحة مقدمي الخدمات للمصممين',
        ]);

        $designersProviders->views()->attach([1]);

        AdvertisingPageAdvertisingView::find(6)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);


        $companysProviders =  AdvertisingPage::create([
            "name" => 'صفحة مقدمي الخدمات لشركات التشطيبات',
        ]);

        $companysProviders->views()->attach([1]);

        AdvertisingPageAdvertisingView::find(7)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);



        $LoqtaPage =  AdvertisingPage::create([
            "name" => 'صفحة لقطة',
        ]);

        $LoqtaPage->views()->attach([1,2]);

        AdvertisingPageAdvertisingView::find(8)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

        AdvertisingPageAdvertisingView::find(9)->size()->create([
            "width" => 450,
            "height" => 250,
        ]);



        $MeasurementPage =  AdvertisingPage::create([
            "name" => 'صفحة خدمه رفع المقاسات',
        ]);

        $MeasurementPage->views()->attach([1,2]);

        AdvertisingPageAdvertisingView::find(10)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

        AdvertisingPageAdvertisingView::find(11)->size()->create([
            "width" => 570,
            "height" => 380,
        ]);



        $AdvisorPage =  AdvertisingPage::create([
            "name" => 'صفحة مستشار شيطبنا',
        ]);

        $AdvisorPage->views()->attach([1,2]);

        AdvertisingPageAdvertisingView::find(12)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

        AdvertisingPageAdvertisingView::find(13)->size()->create([
            "width" => 570,
            "height" => 380,
        ]);


        $DesignerProfile =  AdvertisingPage::create([
            "name" => 'صفحة بروفايل الديزاينر',
        ]);

        $DesignerProfile->views()->attach([1,2]);

        AdvertisingPageAdvertisingView::find(14)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

        AdvertisingPageAdvertisingView::find(15)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);



        $companyProfile =  AdvertisingPage::create([
            "name" => 'صفحة بروفايل الشركات',
        ]);

        $companyProfile->views()->attach([1,2]);

        AdvertisingPageAdvertisingView::find(16)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

        AdvertisingPageAdvertisingView::find(17)->size()->create([
            "width" => 1450,
            "height" => 307,
        ]);

    }
}
