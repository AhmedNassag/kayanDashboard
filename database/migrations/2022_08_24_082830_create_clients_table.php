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
            $table->string("type")->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId("area_id")->nullable()->constrained("areas");
            $table->foreignId('selling_method_id')->default(0)->nullable()->constrained('selling_methods')->cascadeOnDelete();
            $table->string("address")->nullable();
            $table->string("location")->nullable();
            $table->boolean("same_address_shipping")->nullable();
            $table->string("shipping_address")->nullable();
            $table->foreignId('shipping_city_id')->nullable()->constrained('cities');
            $table->foreignId("shipping_area_id")->nullable()->constrained("areas");
            $table->string("shipping_location")->nullable();
            $table->string("responsible_name")->nullable();
            $table->string("responsible_phone")->nullable();
            $table->foreignId('know_us_way_id')->nullable()->constrained('know_us_ways');
            $table->string("whatsup_phone")->nullable();
            $table->string("platform_type")->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');

            /*
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->string('trade_name')->nullable();
            $table->text('address')->nullable();
            $table->text('location')->nullable();
            $table->text('firebase_token')->nullable();
            */
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
