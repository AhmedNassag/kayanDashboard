<?php

namespace Database\Seeders;

use App\Models\FooterLink;
use Illuminate\Database\Seeder;


class FooterLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FooterLink::insert([
            ["name" => "معلومات التوصيل", "image" => null, "content" => null],
            ["name" => "سياسة الخصوصية", "image" => null, "content" => null],
            ["name" => "الاحكام والشروط", "image" => null, "content" => null],
            ["name" => "عائدات", "image" => null, "content" => null],
            ["name" => "الشحن", "image" => null, "content" => null],
        ]);
    }
}
