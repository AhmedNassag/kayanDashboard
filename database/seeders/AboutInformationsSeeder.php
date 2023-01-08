<?php

namespace Database\Seeders;

use App\Models\AboutInformation;
use Illuminate\Database\Seeder;


class AboutInformationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AboutInformation::insert([
            ["id" => 1],
            ["id" => 2],
            ["id" => 3],
            ["id" => 4]
        ]);
    }
}
