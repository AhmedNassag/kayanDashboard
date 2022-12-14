<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('close')->default(false);
            $table->string('phone');
            $table->string('wats_app');
            $table->integer('refund_allowed_days')->default(7);
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('work_time')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->boolean('show_price')->default(true);
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
        Schema::dropIfExists('settings');
    }
}
