<?php

namespace Database\Seeders;

use App\Models\AlsoBought;
use App\Models\BestSeller;
use App\Models\Deal;
use App\Models\MostPopular;
use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(CreateAdminSeeder::class);
        $this->call(ProductStatusSeeder::class);
        $this->call(SellerCategorySeeder::class);
        $this->call(AdvertiseViewSeeder::class);
        $this->call(AdvertisePageSeeder::class);
        $this->call(AdvertisePageMobileSeeder::class);
        $this->call(AdvertisePackageSeeder::class);
        $this->call(AdvertiseScheduleSeeder::class);
        $this->call(AdvertiseSeeder::class);
        $this->call(FooterLinkSeeder::class);
        $this->call(AboutBannerSeeder::class);
        $this->call(MainAccountTableSeeder::class);
        $this->call(SubAccountTableSeeder::class);
        $this->call(AboutSectionSeeder::class);
        $this->call(AboutInformationsSeeder::class);
        Deal::creat(['limit' => 10]);
        BestSeller::creat(['limit' => 10]);
        AlsoBought::creat(['limit' => 10]);
        MostPopular::creat(['limit' => 10]);
        $this->call(OrderStatusTableSeerer::class);
        $this->call(StoreTableSeeder::class);
        $this->call(TreasuryTableSeeder::class);
        $this->call(ExpenseTableSeeder::class);
        $this->call(IncomeTableSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
