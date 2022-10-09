<?php

namespace App\Repositories;

use App\Commons\Consts\ClientType;
use App\Models\City;
use App\Models\Client;
use App\Models\KnowUsWay;
use App\Models\User;

class ClientRepository
{
    public function store($client)
    {
        $user = User::create([
            "name" => $client["name"],
            "email" => $client["email"],
            "status" => 1,
            'phone' => $client["phone"]
        ]);
        $client = Client::create([
            "store_name" => $client["store_name"],
            "city_id" => $client["city_id"],
            "area_id" => $client["area_id"],
            "address" => $client["address"],
            "location" => $client["location"],
            "whatsup_phone" => $client["whatsup_phone"],
            "responsible_name" => $client["responsible_name"],
            "responsible_phone" => $client["responsible_phone"],
            "same_address_shipping" => $client["same_address_shipping"],
            "shipping_city_id" => $client["shipping_city_id"],
            "shipping_address" => $client["shipping_address"],
            "shipping_location" => $client["shipping_location"],
            "shipping_area_id" => $client["shipping_area_id"],
            "user_id" => $user->id,
            "know_us_way_id" => $client["know_us_way_id"],
            "platform_type" => ClientType::DIRECT_SALE
        ]);
        $client->user = $user;
        return $client;
    }

    public function update($clientInput)
    {
        $client = Client::find($clientInput["id"]);
        $client->store_name = $clientInput["store_name"];
        $client->city_id = $clientInput["city_id"];
        $client->area_id = $clientInput["area_id"];
        $client->address = $clientInput["address"];
        $client->location = $clientInput["location"];
        $client->whatsup_phone = $clientInput["whatsup_phone"];
        $client->responsible_name = $clientInput["responsible_name"];
        $client->responsible_phone = $clientInput["responsible_phone"];
        $client->same_address_shipping = $clientInput["same_address_shipping"];
        $client->shipping_address = $clientInput["shipping_address"] ?? null;
        $client->shipping_location = $clientInput["shipping_location"] ?? null;
        $client->shipping_area_id = $clientInput["shipping_area_id"] ?? null;
        $client->shipping_city_id = $clientInput["shipping_city_id"] ?? null;
        $client->know_us_way_id = $clientInput["know_us_way_id"] ?? null;
        $client->save();
        $user = User::find($client->user_id);
        $user->name = $clientInput["name"];
        $user->email = $clientInput["email"];
        $user->phone = $clientInput["phone"];
        $user->save();
        $client->user = $user;
        return $client;
    }

    public function getPage($pageSize, $text)
    {
        return Client::where("platform_type",ClientType::DIRECT_SALE)->whereHas("user", function ($q) use ($text) {
            $q->where("name", "like", "%$text%")
                ->orWhere("phone", $text)
                ->orWhere("email", $text);
        })->with("user")->paginate($pageSize);
    }

    public function toggleActivation($userId)
    {
        $user = User::find($userId);
        $user->status = !$user->status;
        $user->save();
    }

    public function getAllClients()
    {
        return Client::whereHas("user", function ($q) {
            $q->where("status", 1);
        })->with("user")->get();
    }
    public function getCitiesWithAreas()
    {
        return City::with("areas")->get();
    }
    public function getKnowusWays()
    {
        return KnowUsWay::get();
    }
}
