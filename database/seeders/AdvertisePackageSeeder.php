<?php

namespace Database\Seeders;

use App\Models\AdvertisingPackage;
use Faker\Factory;
use Illuminate\Database\Seeder;

class AdvertisePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $Gold = AdvertisingPackage::create([
            'name' => 'Gold',
            'period' => 7,
            'visiter_num' => 1000,
            'price' => 100,
            'status' => true
        ]);

        $Silver = AdvertisingPackage::create([
            'name'=> "Silver",
            'period' => 7,
            'visiter_num' => 1000,
            'price' => 100,
            'status' => true
        ]);

        $Diamond = AdvertisingPackage::create([
            "name"=> "Diamond",
            'period' => 7,
            'visiter_num' => 1000,
            'price' => 100,
            'status' => true
        ]);


    }
}
