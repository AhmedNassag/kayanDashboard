<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("store_name")->nullable();
            $table->string("country")->nullable();
            $table->string("address")->nullable();
            $table->string("location")->nullable();
            $table->string("whatsup_phone")->nullable();
            $table->string("responsible_name")->nullable();
            $table->string("responsible_phone")->nullable();
            $table->boolean("same_address_shipping")->nullable();
            $table->string("shipping_country")->nullable();
            $table->string("shipping_address")->nullable();
            $table->string("shipping_location")->nullable();
            $table->string("shipping_area")->nullable();
            $table->string("shipping_city")->nullable();
            $table->string("platform_type")->nullable();
            $table->string("type")->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId("area_id")->constrained("areas");
            $table->foreignId('know_us_way_id')->nullable()->constrained('know_us_ways');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
