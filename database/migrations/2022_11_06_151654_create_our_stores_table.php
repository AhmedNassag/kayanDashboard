<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_stores', function (Blueprint $table) {
            $table->id();
            $table->string("first_line")->nullable();
            $table->string("second_line")->nullable();
            $table->string("facebook")->nullable();
            $table->string("instgram")->nullable();
            $table->string("youtube")->nullable();
            $table->string("twitter")->nullable();
            $table->string("linked_in")->nullable();
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
        Schema::dropIfExists('our_stores');
    }
}
