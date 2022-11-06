<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingSizeMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_size_mobiles', function (Blueprint $table) {
            $table->id();
            $table->double('width',8,0)->nullable();
            $table->double('height',8,0)->nullable();
            $table->foreignId('page_mobile_view_id')->constrained('advertising_page_mobile_advertising_views')->cascadeOnDelete();
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
        Schema::dropIfExists('advertising_size_mobiles');
    }
}
