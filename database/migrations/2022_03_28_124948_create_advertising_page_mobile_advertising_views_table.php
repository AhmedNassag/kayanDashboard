<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingPageMobileAdvertisingViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_page_mobile_advertising_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId("page_mobile_id")->constrained('advertising_page_mobiles')->cascadeOnDelete();
            $table->foreignId("view_id")->constrained('advertising_views')->cascadeOnDelete();
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
        Schema::dropIfExists('advertising_page_mobile_advertising_views');
    }
}
