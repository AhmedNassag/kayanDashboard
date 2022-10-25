<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingViewsTable extends Migration
{


    public function up()
    {
        Schema::create('advertising_views', function (Blueprint $table) {
            $table->id();

            $table->string('type');
            // $table->foreignId('view_id')->constrained('advertising_views')->cascadeOnDelete();
            // $table->unique('view_id');

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
        Schema::dropIfExists('advertising_views');
    }
}
