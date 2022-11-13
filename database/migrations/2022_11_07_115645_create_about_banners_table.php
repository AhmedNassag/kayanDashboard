<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_banners', function (Blueprint $table) {
            //There are three banners
            $table->id();
            $table->string("name")->nullable();
            $table->string("header")->nullable();
            $table->string("url")->nullable();
            $table->string("button_label")->nullable();
            $table->string("image")->nullable();
            $table->string("video")->nullable();
            $table->string("sub_header")->nullable(); //For last banner
            $table->string("first_text")->nullable(); //For first and last banner
            $table->string("second_text")->nullable(); //For first and last banner
            $table->string("color")->nullable(); //For first and second banner
            $table->string("text")->nullable(); //For middle banner
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
        Schema::dropIfExists('about_banners');
    }
}
