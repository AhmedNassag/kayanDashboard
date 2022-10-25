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
        $above = AdvertisingView::create([
            "type" => 'فوق'
        ]);


        $under = AdvertisingView::create([
            "type" => 'تحت'
        ]);


        $middle = AdvertisingView::create([
            "type" => 'الوسط'
        ]);

    }
}
