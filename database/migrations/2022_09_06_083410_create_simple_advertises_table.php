<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimpleAdvertisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simple_advertises', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("image")->nullable();
            $table->string("url")->nullable();
            $table->boolean("external")->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products');
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
        Schema::dropIfExists('simple_advertises');
    }
}
