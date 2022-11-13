<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_information', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("header")->nullable();
            $table->string("sub_header")->nullable();
            $table->string("text")->nullable();
            $table->string("first_feature")->nullable();
            $table->string("second_feature")->nullable();
            $table->string("third_feature")->nullable();
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
        Schema::dropIfExists('about_information');
    }
}
