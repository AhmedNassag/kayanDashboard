<?php

namespace Database\Seeders;

use App\Models\AboutBanner;
use App\Models\FooterLink;
use Illuminate\Database\Seeder;


class AboutBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AboutBanner::insert([
            ["name" => "FIRST_BANNER"],
            ["name" => "SECOND_BANNER"],
            ["name" => "THIRD_BANNER"],
        ]);
    }
}
