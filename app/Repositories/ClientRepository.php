<?php

namespace App\Repositories;

use App\Models\Client;
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
            "country" => $client["country"],
            "city" => $client["city"],
            "address" => $client["address"],
            "location" => $client["location"],
            "area" => $client["area"],
            "whatsup_phone" => $client["whatsup_phone"],
            "responsible_name" => $client["responsible_name"],
            "responsible_phone" => $client["responsible_phone"],
            "same_address_shipping" => $client["same_address_shipping"],
            "shipping_country" => $client["shipping_country"],
            "shipping_city" => $client["shipping_city"],
            "shipping_address" => $client["shipping_address"],
            "shipping_location" => $client["shipping_location"],
            "shipping_area" => $client["shipping_area"],
            "user_id" => $user->id
        ]);
        $client->user = $user;
        return $client;
    }

    public function update($clientInput)
    {
        $client = Client::find($clientInput["id"]);
        $client->store_name = $clientInput["store_name"];
        $client->country = $clientInput["country"];
        $client->city = $clientInput["city"];
        $client->address = $clientInput["address"];
        $client->location = $clientInput["location"];
        $client->area = $clientInput["area"];
        $client->whatsup_phone = $clientInput["whatsup_phone"];
        $client->responsible_name = $clientInput["responsible_name"];
        $client->responsible_phone = $clientInput["responsible_phone"];
        $client->same_address_shipping = $clientInput["same_address_shipping"];
        $client->shipping_country = $clientInput["shipping_country"] ?? null;
        $client->shipping_address = $clientInput["shipping_address"] ?? null;
        $client->shipping_location = $clientInput["shipping_location"] ?? null;
        $client->shipping_area = $clientInput["shipping_area"] ?? null;
        $client->shipping_city = $clientInput["shipping_city"] ?? null;
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
        return Client::whereHas("user", function ($q) use ($text) {
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
}
