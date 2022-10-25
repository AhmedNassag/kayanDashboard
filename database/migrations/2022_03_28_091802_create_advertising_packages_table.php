<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_packages', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            // $table->foreignId('package_id')->constrained('advertising_packages')->cascadeOnDelete();
            // $table->unique('package_id');

            $table->double('period');
            $table->double('visiter_num');
            $table->double('price');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('advertising_packages');
    }
}
