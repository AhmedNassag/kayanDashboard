<?php

namespace Database\Seeders;

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
        $this->call(AboutSectionSeeder::class);
    }
}
