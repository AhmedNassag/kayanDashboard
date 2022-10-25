<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingPageMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_page_mobiles', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            // $table->foreignId('page_id')->constrained('advertising_page_mobiles')->cascadeOnDelete();
            // $table->unique('page_id');

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
        Schema::dropIfExists('advertising_page_mobiles');
    }
}
