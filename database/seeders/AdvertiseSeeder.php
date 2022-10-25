<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdvertiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();


//        for ($i = 1; $i <= 50; $i++) {
//
//            // start create user
//            $user = User::create([
//                "name" => $faker->name,
//                "email" => $faker->unique()->safeEmail,
//                "password" => 12345678,
//                "auth_id" => 2,
//                'role_name' => ['advertiser'],
//                "status" => 1,
//                'phone' => $faker->numberBetween(9000, 1000000),
//                "code" => '+2',
//                'remember_token' => Str::random(10),
//                'created_at' => now(),
//                'updated_at' => now(),
//            ]);
//
//            $user->complement()->create([
//                'nameCompany' => $faker->unique()->name,
//                'country_id' => 1,
//                'state_id' => rand(1, 11),
//                'device' => rand(0, 1)
//            ]);
//
//            Advertiser::create([
//                'user_id' => $user->id
//            ]);
//
//            $user->media()->create([
//                'file_name' => 'img-04.jpg',
//                'file_size' => 7664,
//                'file_type' => 'png/image',
//                'file_sort' => 1
//            ]);
//        }

    }
}
