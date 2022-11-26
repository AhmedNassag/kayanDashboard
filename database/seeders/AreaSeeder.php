<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create([
            "name"=>"المرج",
            "city_id"=>1,
            'shipping_price' => 100
        ]);

        Area::create([
            "name"=>"عين شمس",
            "city_id"=>1,
            'shipping_price' => 100
        ]);
        Area::create([
            "name"=>"مدينة نصر",
            "city_id"=>1,
            'shipping_price' => 100
        ]);

        Area::create([
            "name"=>"الدقى",
            "city_id"=>2,
            'shipping_price' => 100
        ]);
        Area::create([
            "name"=>"الهرم",
            "city_id"=>2,
            'shipping_price' => 100
        ]);
        Area::create([
            "name"=>"فيصل",
            "city_id"=>2,
            'shipping_price' => 100
        ]);
    }
}
