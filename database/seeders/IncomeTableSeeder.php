<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Income::create([
            'name'=>'طلبات الاونلاين',
        ]);

        Income::create([
            'name'=>'الاعلاناعت',
        ]);

        // Income::create([
        //     'name'=>'المبيعات',
        // ]);

        // Income::create([
        //     'name'=>'حساب جارى شريك',
        // ]);
    }
}
