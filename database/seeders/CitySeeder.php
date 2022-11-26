<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            "name"=>"القاهرة",
            "available"=>1
        ]);

        City::create([
            "name"=>"الجيزة",
            "available" => 1
        ]);
    }
}
