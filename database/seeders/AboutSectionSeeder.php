<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use App\Models\FooterLink;
use Illuminate\Database\Seeder;


class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AboutSection::insert([
            ["name" => "TOP_SECTION"],
            ["name" => "FIRST_SECTION"],
            ["name" => "SECOND_SECTION"],
            ["name" => "THIRD_SECTION"]
        ]);
    }
}
