<?php

namespace Database\Seeders;

use App\Models\AdvertisingView;
use Illuminate\Database\Seeder;

class AdvertiseViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $firstSlider = AdvertisingView::create([ "type" => 'أول سلايدر' ]);

        $secondSlider = AdvertisingView::create([ "type" => 'ثانى سلايدر' ]);

        $thirdSlider = AdvertisingView::create([ "type" => 'ثالث سلايدر' ]);

        $fourthSlider = AdvertisingView::create([ "type" => 'رابع سلايدر' ]);

        $rightSquare = AdvertisingView::create([ "type" => 'مربع يمين' ]);

        $middleSquare = AdvertisingView::create([ "type" => 'مربع وسط' ]);

        $leftSquare = AdvertisingView::create([ "type" => 'مربع يسار' ]);

        $topBanner = AdvertisingView::create([ "type" => 'بانر فوق' ]);

        $middleBanner = AdvertisingView::create([ "type" => 'بانر وسط' ]);

        $bottomBanner = AdvertisingView::create([ "type" => 'بانر تحت' ]);

        $popUp = AdvertisingView::create([ "type" => 'بوب أب' ]);

        $topRightRectangle = AdvertisingView::create([ "type" => 'مستطيل أعلى اليمين' ]);

        $bottomRightRectangle = AdvertisingView::create([ "type" => 'مستطيل أسفل اليمين' ]);

        $bottom = AdvertisingView::create(["type" => 'تحت']);

        $top = AdvertisingView::create([ "type" => 'فوق' ]);

    }
}
