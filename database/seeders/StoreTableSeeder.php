<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([
            'name' => 'المخزن الرئيسى',
            'phone' => '01234567890',
            'city_id' => 1,
            'area_id' => 1,
            'main' => 1,
        ]);
    }
}
